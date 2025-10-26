<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $users = User::with('roles')
            ->when($search, function($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        DB::transaction(function() use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'password_set' => true,
            ]);

            if ($request->has('roles')) {
                $user->roles()->sync($request->roles);
            }
        });

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        DB::transaction(function() use ($request, $user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->has('roles')) {
                $user->roles()->sync($request->roles);
            }
        });

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        DB::transaction(function() use ($user) {
            $user->roles()->detach();
            $user->delete();
        });

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'password_set' => true,
        ]);

        return redirect()->route('admin.users.show', $user)
                        ->with('success', 'Contraseña actualizada exitosamente.');
    }

    /**
     * Update email verification status
     */
    public function updateVerification(User $user)
    {
        $user->update([
            'email_verified_at' => $user->email_verified_at ? null : now()
        ]);

        $status = $user->email_verified_at ? 'verificado' : 'pendiente';

        return redirect()->route('admin.users.show', $user)
                        ->with('success', "Estado de verificación actualizado a {$status}.");
    }

    /**
     * Generate temporal token
     */
    public function generateTemporalToken(User $user)
    {
        $temporalToken = Str::random(60);
        
        $user->update([
            'temporal_token' => Hash::make($temporalToken),
            'temporal_token_created_at' => now()
        ]);

        return redirect()->route('admin.users.show', $user)
                        ->with('success', 'Token temporal generado exitosamente.')
                        ->with('temporal_token', $temporalToken);
    }

    /**
     * Show user profile
     */
    public function profile(User $user)
    {
        $user->load('roles');
        return view('admin.users.profile', compact('user'));
    }
}