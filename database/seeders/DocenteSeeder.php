<?php

namespace Database\Seeders;

use App\Models\Docente;
use Illuminate\Database\Seeder;

class DocenteSeeder extends Seeder
{
    public function run()
    {
        Docente::create([
            'codigo' => 'DOC001',
            'fecha_contrato' => '2025-01-12',
            'sueldo' => 8000.00,
            'telefono' => '78111662',
            'id_users' => 2
        ]);

        Docente::create([
            'codigo' => 'DOC002',
            'fecha_contrato' => '2025-01-15',
            'sueldo' => 7500.00,
            'telefono' => '78111663',
            'id_users' => 3
        ]);
    }
}