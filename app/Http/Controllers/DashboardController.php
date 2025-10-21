<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Redirección basada en roles
        if ($user->hasRole('admin')) {
            return view('admin.dashboard'); // o redirect('/admin')
        } elseif ($user->hasRole('coordinador')) {
            return view('coordinador.dashboard');
        } elseif ($user->hasRole('docente')) {
            return view('docente.dashboard');
        }
        
        // Vista genérica si no tiene un rol específico
        return view('dashboard');
    }
}