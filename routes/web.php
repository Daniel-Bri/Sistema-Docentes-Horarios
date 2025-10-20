<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Incluimos rutas de autenticación
require __DIR__.'/auth.php';

// Rutas protegidas por roles
require __DIR__.'/admin.php';
require __DIR__.'/docente.php';
require __DIR__.'/coordinador.php';
