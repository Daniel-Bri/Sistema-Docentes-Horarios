<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\GestionDeHorarios\HorariosController;
use App\Http\Controllers\GestionDeHorarios\AsignacionAutomaticaController;
use App\Http\Controllers\AnalisisYReportes\ReportesAulasController;

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
            // CRUD Básico
            Route::get('/', [MateriaController::class, 'index'])->name('index');
            Route::get('/create', [MateriaController::class, 'create'])->name('create');
            Route::post('/', [MateriaController::class, 'store'])->name('store');
            Route::get('/{sigla}', [MateriaController::class, 'show'])->name('show');
            Route::get('/{sigla}/edit', [MateriaController::class, 'edit'])->name('edit');
            Route::put('/{sigla}', [MateriaController::class, 'update'])->name('update');
            
            // Asignación de Grupos - Coordinador
            Route::get('/{sigla}/asignar-grupo', [MateriaController::class, 'asignarGrupo'])->name('asignar-grupo');
            Route::post('/{sigla}/asignar-grupo', [MateriaController::class, 'storeAsignarGrupo'])->name('store-asignar-grupo');
            
            // Horarios
            Route::get('/{sigla}/horarios', [MateriaController::class, 'horarios'])->name('horarios');
        });

        // API Routes para Materias - Coordinador (SIN PREFIJO API)
        Route::prefix('materias')->name('materias.')->group(function () {
            Route::get('/get-horarios', [MateriaController::class, 'getHorarios'])->name('get-horarios');
            Route::get('/get-aulas', [MateriaController::class, 'getAulas'])->name('get-aulas');
        });

        // =========================================================================
        // GESTIÓN DE HORARIOS - COORDINADOR (TUS RUTAS NUEVAS)
        // =========================================================================
        // Rutas específicas DEBEN IR ANTES del resource
        Route::get('/horarios/asignar', [HorariosController::class, 'asignar'])->name('horarios.asignar');
        Route::post('/horarios/asignar', [HorariosController::class, 'store'])->name('horarios.store');
        
        // Resource DEBE IR DESPUÉS de las rutas específicas
        Route::resource('/horarios', HorariosController::class);

        // =========================================================================
        // ASIGNACIÓN AUTOMÁTICA DE HORARIOS - NUEVAS RUTAS
        // =========================================================================
        Route::prefix('asignacion-automatica')->name('asignacion-automatica.')->group(function () {
            Route::get('/', [AsignacionAutomaticaController::class, 'index'])->name('index');
            Route::post('/completa', [AsignacionAutomaticaController::class, 'asignacionCompleta'])->name('completa');
            Route::post('/inteligente', [AsignacionAutomaticaController::class, 'asignacionInteligente'])->name('inteligente');
        });

        Route::prefix('reportes')->name('reportes.')->group(function () {
            Route::prefix('aulas')->name('aulas.')->group(function () {
                // Vista principal
                Route::get('/disponibles', [\App\Http\Controllers\AnalisisYReportes\ReporteAulasController::class, 'index'])->name('disponibles');
                
                // Generar reporte
                Route::post('/disponibles/generar', [\App\Http\Controllers\AnalisisYReportes\ReporteAulasController::class, 'generarReporte'])->name('disponibles.generar');
                
                // Exportar PDF
                Route::get('/disponibles/pdf', [\App\Http\Controllers\AnalisisYReportes\ReporteAulasController::class, 'generarPDF'])->name('disponibles.pdf');
            });
        });
    });