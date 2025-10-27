<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MateriaController; // ← Mismo controlador existente

Route::prefix('coordinador')
    ->middleware(['auth', 'role:coordinador'])
    ->as('coordinador.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('coordinador.dashboard');
        })->name('dashboard');

        // =========================================================================
        // GESTIÓN DE MATERIAS - COORDINADOR (CON CONTROLADOR EXISTENTE)
        // =========================================================================
        Route::prefix('materias')->name('materias.')->group(function () {
            Route::get('/', [MateriaController::class, 'index'])->name('index');
            Route::get('/create', [MateriaController::class, 'create'])->name('create');
            Route::post('/', [MateriaController::class, 'store'])->name('store');
            Route::get('/{sigla}', [MateriaController::class, 'show'])->name('show');
            Route::get('/{sigla}/edit', [MateriaController::class, 'edit'])->name('edit');
            Route::put('/{sigla}', [MateriaController::class, 'update'])->name('update');
            
            // Asignación de Grupos - Coordinador
            Route::get('/{sigla}/asignar-grupo', [MateriaController::class, 'asignarGrupo'])->name('asignar-grupo');
            Route::post('/{sigla}/asignar-grupo', [MateriaController::class, 'storeAsignacionGrupo'])->name('store-asignacion-grupo');
            
            // Reportes - Solo Coordinador
            Route::get('/{sigla}/reporte', [MateriaController::class, 'generarReporte'])->name('reporte');
            Route::get('/reporte/general', [MateriaController::class, 'reporteGeneral'])->name('reporte.general');
        });

        // API Routes para Materias - Coordinador
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/materias/horarios', [MateriaController::class, 'getHorarios'])->name('materias.horarios');
            Route::get('/materias/aulas', [MateriaController::class, 'getAulas'])->name('materias.aulas');
            Route::get('/materias/docentes', [MateriaController::class, 'getDocentes'])->name('materias.docentes');
        });
    });