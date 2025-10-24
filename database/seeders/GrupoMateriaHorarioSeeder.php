<?php

namespace Database\Seeders;

use App\Models\GrupoMateriaHorario;
use Illuminate\Database\Seeder;

class GrupoMateriaHorarioSeeder extends Seeder
{
    public function run()
    {
        GrupoMateriaHorario::create([
            'id_grupo_materia' => 1, // SA - INF110
            'id_horario' => 1, // LUN 08:30-10:00
            'id_aula' => 1, // Aula 24
            'estado_aula' => 'ocupado'
        ]);

        GrupoMateriaHorario::create([
            'id_grupo_materia' => 1, // SA - INF110
            'id_horario' => 3, // MIE 08:30-10:00
            'id_aula' => 1, // Aula 24
            'estado_aula' => 'ocupado'
        ]);

        GrupoMateriaHorario::create([
            'id_grupo_materia' => 2, // SB - INF110
            'id_horario' => 2, // LUN 10:30-12:00
            'id_aula' => 2, // Aula 41 (Laboratorio)
            'estado_aula' => 'ocupado'
        ]);
    }
}