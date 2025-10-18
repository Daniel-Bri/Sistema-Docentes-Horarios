<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Datos de ejemplo para el dashboard
        return view('dashboard.index', [
            // KPIs principales
            'totalDocentes' => 24,
            'totalMaterias' => 45,
            'asistenciaHoy' => 87,
            'conflictosHorarios' => 2,
            
            // Estado de aulas
            'estadoAulas' => [
                'disponibles' => 8,
                'en_uso' => 12,
                'mantenimiento' => 2,
                'total' => 22
            ],
            
            'now' => now()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
