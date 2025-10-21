<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocenteSeeder extends Seeder
{
    public function run()
    {
        // Primero necesitas crear la tabla categorías si no existe
        if (!DB::table('categorias')->exists()) {
            DB::table('categorias')->insert([
                ['nombre' => 'Titular'],
                ['nombre' => 'Asociado'],
                ['nombre' => 'Auxiliar'],
                ['nombre' => 'Instructor'],
            ]);
        }

        // Luego crear los registros de docentes
        // Nota: Esto requiere que los usuarios ya existan
        $users = DB::table('users')->whereIn('email', [
            'juan.perez@sistema.edu',
            'ana.garcia@sistema.edu', 
            'carlos.lopez@sistema.edu'
        ])->get();

        foreach ($users as $user) {
            DB::table('docentes')->insert([
                'codigo' => 'DOC' . $user->id,
                'fecha_contratado' => now()->subYears(rand(1, 10)),
                'sueldo' => rand(3000, 8000),
                'user_id' => $user->id,
                'categoria_id' => rand(1, 4),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}