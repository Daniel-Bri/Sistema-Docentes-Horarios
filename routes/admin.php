<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GestionAcademica\AulaController;
use App\Http\Controllers\Administracion\BitacoraController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\UserController;

// Panel administrativo (solo usuarios con rol “admin” o permiso “ver-bitacora”)
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->as('admin.')
    ->group(function () {
        
        // Bitácora de auditoría
        Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
        Route::get('/bitacora/{id}', [BitacoraController::class, 'show'])->name('bitacora.show');
        Route::get('/bitacora/exportar', [BitacoraController::class, 'exportar'])->name('bitacora.exportar');
        Route::post('/bitacora/limpiar', [BitacoraController::class, 'limpiar'])->name('bitacora.limpiar');

        // Gestión de Roles
        Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
        Route::get('/roles/{id}', [RolController::class, 'show'])->name('roles.show');
        Route::get('/roles/{id}/edit', [RolController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{id}', [RolController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{id}', [RolController::class, 'destroy'])->name('roles.destroy');

        // RUTAS DE USUARIOS - MOVER AQUÍ (FUERA del grupo de docentes)
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/profile', [UserController::class, 'profile'])->name('users.profile');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // Rutas adicionales de usuarios
        Route::post('/users/{user}/password', [UserController::class, 'updatePassword'])->name('users.update-password');
        Route::post('/users/{user}/verification', [UserController::class, 'updateVerification'])->name('users.update-verification');
        Route::post('/users/{user}/generate-token', [UserController::class, 'generateTemporalToken'])->name('users.generate-token');

        // Rutas para carga horaria de docentes (ESTAS SÍ van con su propio prefijo)
        Route::prefix('docentes')->name('docentes.')->group(function () {
            Route::get('/{codigo}/carga-horaria', [DocenteController::class, 'cargaHoraria'])->name('carga-horaria');
            Route::post('/{codigo}/asignar-grupo', [DocenteController::class, 'asignarGrupo'])->name('asignar-grupo');
            Route::delete('/{codigo}/eliminar-grupo/{grupoMateriaId}', [DocenteController::class, 'eliminarGrupo'])->name('eliminar-grupo');
        });

        // Gestión de Aulas
        Route::resource('aulas', AulaController::class)->names('aulas');
    });