<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Administracion\BitacoraController;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        // Si es coordinador, solo ver usuarios de su misma carrera
        if (Auth::user()->hasRole('coordinador')) {
            $users = User::with('roles')
                ->whereHas('carrera', function($query) {
                    $query->where('id', Auth::user()->carrera_id);
                })
                ->when($search, function($query) use ($search) {
                    return $query->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                })
                ->orderBy('name', 'asc')
                ->paginate(10);
        } else {
            // Admin ve todos los usuarios
            $users = User::with('roles')
                ->when($search, function($query) use ($search) {
                    return $query->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                })
                ->orderBy('name', 'asc')
                ->paginate(10);
        }

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Coordinador solo puede crear docentes
        if (Auth::user()->hasRole('coordinador')) {
            $roles = Role::where('name', 'docente')->get();
        } else {
            $roles = Role::all();
        }
        
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validaciones específicas por rol
        if (Auth::user()->hasRole('coordinador')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'roles' => 'required|array|size:1',
                'roles.*' => 'in:docente' // Solo puede asignar rol docente
            ]);
        } else {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id'
            ]);
        }

        DB::transaction(function() use ($request) {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'password_set' => true,
                'email_verified_at' => now(),
            ];

            // Si es coordinador, asignar la misma carrera
            if (Auth::user()->hasRole('coordinador')) {
                $userData['carrera_id'] = Auth::user()->carrera_id;
            }

            $user = User::create($userData);

            if ($request->has('roles')) {
                $user->roles()->sync($request->roles);
            }

            // Registrar en bitácora
            BitacoraController::registrarCreacion(
                'Usuario', 
                $user->id, 
                Auth::id(),
                "Usuario {$user->name} creado por " . Auth::user()->name
            );
        });

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Coordinador solo puede ver usuarios de su carrera
        if (Auth::user()->hasRole('coordinador') && $user->carrera_id !== Auth::user()->carrera_id) {
            abort(403, 'No tienes permiso para ver este usuario.');
        }

        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Coordinador solo puede editar usuarios de su carrera
        if (Auth::user()->hasRole('coordinador') && $user->carrera_id !== Auth::user()->carrera_id) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        // Coordinador solo puede asignar rol docente
        if (Auth::user()->hasRole('coordinador')) {
            $roles = Role::where('name', 'docente')->get();
        } else {
            $roles = Role::all();
        }

        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Coordinador solo puede editar usuarios de su carrera
        if (Auth::user()->hasRole('coordinador') && $user->carrera_id !== Auth::user()->carrera_id) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        // Validaciones específicas por rol
        if (Auth::user()->hasRole('coordinador')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
                'roles' => 'required|array|size:1',
                'roles.*' => 'in:docente' // Solo puede asignar rol docente
            ]);
        } else {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id'
            ]);
        }

        DB::transaction(function() use ($request, $user) {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
                $updateData['password_set'] = true;
            }

            $user->update($updateData);

            if ($request->has('roles')) {
                $user->roles()->sync($request->roles);
            }

            // Registrar en bitácora
            BitacoraController::registrarActualizacion(
                'Usuario', 
                $user->id, 
                Auth::id(),
                "Usuario {$user->name} actualizado por " . Auth::user()->name
            );
        });

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Coordinador NO puede eliminar usuarios
        if (Auth::user()->hasRole('coordinador')) {
            abort(403, 'No tienes permiso para eliminar usuarios.');
        }

        DB::transaction(function() use ($user) {
            $userName = $user->name;
            $user->roles()->detach();
            $user->delete();

            // Registrar en bitácora
            BitacoraController::registrarEliminacion(
                'Usuario', 
                $user->id, 
                Auth::id(),
                "Usuario {$userName} eliminado por " . Auth::user()->name
            );
        });

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request, User $user)
    {
        // Coordinador solo puede resetear contraseñas de usuarios de su carrera
        if (Auth::user()->hasRole('coordinador') && $user->carrera_id !== Auth::user()->carrera_id) {
            abort(403, 'No tienes permiso para resetear la contraseña de este usuario.');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'password_set' => true,
        ]);

        // Registrar en bitácora
        BitacoraController::registrarActualizacion(
            'Usuario', 
            $user->id, 
            Auth::id(),
            "Contraseña actualizada para usuario {$user->name} por " . Auth::user()->name
        );

        return redirect()->route('admin.users.index')
                        ->with('success', 'Contraseña actualizada exitosamente.');
    }

    /**
     * Update email verification status
     */
    public function updateVerification(User $user)
    {
        // Coordinador NO puede verificar emails
        if (Auth::user()->hasRole('coordinador')) {
            abort(403, 'No tienes permiso para verificar emails.');
        }

        $newStatus = $user->email_verified_at ? null : now();
        $statusText = $newStatus ? 'verificado' : 'pendiente';
        
        $user->update([
            'email_verified_at' => $newStatus
        ]);

        // Registrar en bitácora
        BitacoraController::registrarActualizacion(
            'Usuario', 
            $user->id, 
            Auth::id(),
            "Estado de verificación actualizado a {$statusText} para usuario {$user->name}"
        );

        return redirect()->route('admin.users.show', $user)
                        ->with('success', "Estado de verificación actualizado a {$statusText}.");
    }

    /**
     * Generate temporal token
     */
    public function generateTemporalToken(User $user)
    {
        // Coordinador NO puede generar tokens
        if (Auth::user()->hasRole('coordinador')) {
            abort(403, 'No tienes permiso para generar tokens temporales.');
        }

        $temporalToken = Str::random(60);
        
        $user->update([
            'temporal_token' => Hash::make($temporalToken),
            'temporal_token_created_at' => now()
        ]);

        // Registrar en bitácora
        BitacoraController::registrarActualizacion(
            'Usuario', 
            $user->id, 
            Auth::id(),
            "Token temporal generado para usuario {$user->name}"
        );

        return redirect()->route('admin.users.show', $user)
                        ->with('success', 'Token temporal generado exitosamente.')
                        ->with('temporal_token', $temporalToken);
    }
}