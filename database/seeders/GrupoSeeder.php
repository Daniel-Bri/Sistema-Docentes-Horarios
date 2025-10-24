<?php

namespace Database\Seeders;

use App\Models\Grupo;
use Illuminate\Database\Seeder;

class GrupoSeeder extends Seeder
{
    public function run()
    {
        Grupo::create(['nombre' => 'SA', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'SB', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'SC', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'SD', 'gestion' => '2025']);
    }
}