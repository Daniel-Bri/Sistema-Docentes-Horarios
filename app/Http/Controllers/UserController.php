<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Grupo;
use App\Models\Carrera;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the users with search functionality.
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'docente']);

        // Búsqueda por nombre, email o ID
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        // Filtro por estado de verificación de email
        if ($request->has('email_verified') && $request->email_verified !== '') {
            if ($request->email_verified == 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->email_verified == 'not_verified') {
                $query->whereNull('email_verified_at');
            }
        }

        // Filtro por rol
        if ($request->has('role') && !empty($request->role)) {
            $query->role($request->role);
        }

        $users = $query->orderBy('name')->paginate(10);
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        $grupos = Grupo::where('gestion', date('Y'))->get();
        $carreras = Carrera::all();
        
        return view('users.create', compact('roles', 'grupos', 'carreras'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|exists:roles,name',
            'codigo_docente' => 'nullable|required_if:role,docente|string|max:50|unique:docente,codigo',
            'telefono' => 'nullable|string|max:20',
            'sueldo' => 'nullable|numeric|min:0',
            'carreras' => 'nullable|array',
            'carreras.*' => 'exists:carrera,id',
        ]);

        DB::transaction(function () use ($validated) {
            // Crear usuario
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(), // Auto-verificar para administradores
            ]);

            // Asignar rol
            $user->assignRole($validated['role']);

            // Si es docente, crear registro en tabla docente
            if ($validated['role'] === 'docente' && !empty($validated['codigo_docente'])) {
                $docente = Docente::create([
                    'codigo' => $validated['codigo_docente'],
                    'fecha_contrato' => now(),
                    'sueldo' => $validated['sueldo'] ?? 0,
                    'telefono' => $validated['telefono'] ?? null,
                    'id_users' => $user->id,
                ]);

                // Asignar carreras al docente si se proporcionaron
                if (!empty($validated['carreras'])) {
                    $docente->carreras()->sync($validated['carreras']);
                }
            }
        });

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['roles', 'docente.carreras', 'docente.gruposMateria']);
        
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $grupos = Grupo::where('gestion', date('Y'))->get();
        $carreras = Carrera::all();
        
        $user->load(['docente.carreras']);
        
        return view('users.edit', compact('user', 'roles', 'grupos', 'carreras'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:8',
            'role' => 'required|exists:roles,name',
            'codigo_docente' => 'nullable|required_if:role,docente|string|max:50|unique:docente,codigo,' . ($user->docente ? $user->docente->codigo : 'NULL') . ',codigo',
            'telefono' => 'nullable|string|max:20',
            'sueldo' => 'nullable|numeric|min:0',
            'carreras' => 'nullable|array',
            'carreras.*' => 'exists:carrera,id',
        ]);

        DB::transaction(function () use ($validated, $user) {
            // Actualizar datos básicos del usuario
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            // Actualizar contraseña si se proporcionó
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Sincronizar roles
            $user->syncRoles([$validated['role']]);

            // Manejar datos de docente
            if ($validated['role'] === 'docente') {
                if ($user->docente) {
                    // Actualizar docente existente
                    $user->docente->update([
                        'codigo' => $validated['codigo_docente'],
                        'telefono' => $validated['telefono'] ?? null,
                        'sueldo' => $validated['sueldo'] ?? 0,
                    ]);

                    // Sincronizar carreras
                    if (isset($validated['carreras'])) {
                        $user->docente->carreras()->sync($validated['carreras']);
                    }
                } else {
                    // Crear nuevo registro de docente
                    $docente = Docente::create([
                        'codigo' => $validated['codigo_docente'],
                        'fecha_contrato' => now(),
                        'sueldo' => $validated['sueldo'] ?? 0,
                        'telefono' => $validated['telefono'] ?? null,
                        'id_users' => $user->id,
                    ]);

                    // Asignar carreras
                    if (!empty($validated['carreras'])) {
                        $docente->carreras()->sync($validated['carreras']);
                    }
                }
            } else {
                // Si el rol cambió y ya no es docente, eliminar registro de docente si existe
                if ($user->docente) {
                    $user->docente->carreras()->detach();
                    $user->docente->delete();
                }
            }
        });

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            // Eliminar datos de docente si existen
            if ($user->docente) {
                $user->docente->carreras()->detach();
                $user->docente->delete();
            }

            // Eliminar roles y permisos
            $user->roles()->detach();
            $user->permissions()->detach();

            // Eliminar usuario
            $user->delete();
        });

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Activar/Desactivar usuario (usando email_verified_at como estado)
     */
    public function toggleStatus(User $user)
    {
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $message = 'Usuario desactivado exitosamente.';
        } else {
            $user->update(['email_verified_at' => now()]);
            $message = 'Usuario activado exitosamente.';
        }

        return redirect()->route('users.index')
            ->with('success', $message);
    }

    /**
     * Forzar verificación de email
     */
    public function verifyEmail(User $user)
    {
        $user->update(['email_verified_at' => now()]);

        return redirect()->route('users.index')
            ->with('success', 'Email verificado manualmente.');
    }

    /**
     * Mostrar perfil del docente
     */
    public function showDocenteProfile(Docente $docente)
    {
        $docente->load(['user', 'carreras', 'gruposMateria.grupo', 'gruposMateria.materia']);
        
        return view('users.docente-profile', compact('docente'));
    }

    /**
     * Obtener usuarios por rol (API para selects)
     */
    public function getByRole($role)
    {
        $users = User::role($role)->get(['id', 'name', 'email']);
        
        return response()->json($users);
    }
}