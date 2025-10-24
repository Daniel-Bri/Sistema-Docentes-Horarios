<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Seeder;

class CarreraSeeder extends Seeder
{
    public function run()
    {
        Carrera::create(['nombre' => 'Ingeniería en Sistemas']);
        Carrera::create(['nombre' => 'Ingeniería Informática']);
        Carrera::create(['nombre' => 'Ciencia de la Computación']);
        Carrera::create(['nombre' => 'Tecnologías de la Información']);
    }
}