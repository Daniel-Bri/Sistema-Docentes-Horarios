<?php

namespace Database\Seeders;

use App\Models\Aula;
use Illuminate\Database\Seeder;

class AulaSeeder extends Seeder
{
    public function run()
    {
        Aula::create([
            'nombre' => 'Aula 24',
            'capacidad' => 50,
            'estado' => 'disponible',
            'tipo' => 'aula'
        ]);

        Aula::create([
            'nombre' => 'Aula 41',
            'capacidad' => 50,
            'estado' => 'disponible',
            'tipo' => 'laboratorio'
        ]);

        Aula::create([
            'nombre' => 'Aula 15',
            'capacidad' => 30,
            'estado' => 'disponible',
            'tipo' => 'aula'
        ]);

        Aula::create([
            'nombre' => 'Auditorio Principal',
            'capacidad' => 100,
            'estado' => 'disponible',
            'tipo' => 'auditorio'
        ]);
    }
}