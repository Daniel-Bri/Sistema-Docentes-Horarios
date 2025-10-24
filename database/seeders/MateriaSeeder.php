<?php

namespace Database\Seeders;

use App\Models\Materia;
use Illuminate\Database\Seeder;

class MateriaSeeder extends Seeder
{
    public function run()
    {
        Materia::create([
            'sigla' => 'INF110',
            'nombre' => 'Introducción a la Informática',
            'semestre' => 1,
            'id_categoria' => 1
        ]);

        Materia::create([
            'sigla' => 'INF119',
            'nombre' => 'Programación I',
            'semestre' => 1,
            'id_categoria' => 1
        ]);

        Materia::create([
            'sigla' => 'INF120',
            'nombre' => 'Programación II',
            'semestre' => 2,
            'id_categoria' => 1
        ]);

        Materia::create([
            'sigla' => 'MAT115',
            'nombre' => 'Cálculo I',
            'semestre' => 1,
            'id_categoria' => 2
        ]);
    }
}