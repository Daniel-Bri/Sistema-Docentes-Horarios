<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Docente 1
        $docente1 = User::firstOrCreate(
            ['email' => 'juan.perez@sistema.edu'],
            [
                'name' => 'Juan Carlos Pérez',
                'password' => Hash::make('Docente123*'),
                'email_verified_at' => now(),
            ]
        );

        // Asignar rol docente si no lo tiene
        if (!$docente1->hasRole('docente')) {
            $docenteRole = DB::table('roles')->where('name', 'docente')->first();
            if ($docenteRole) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $docenteRole->id,
                    'model_type' => 'App\Models\User',
                    'model_id' => $docente1->id,
                ]);
            }
        }

        // Docente 2
        $docente2 = User::firstOrCreate(
            ['email' => 'ana.garcia@sistema.edu'],
            [
                'name' => 'Ana María García', 
                'password' => Hash::make('Docente123*'),
                'email_verified_at' => now(),
            ]
        );

        // Asignar rol docente si no lo tiene
        if (!$docente2->hasRole('docente')) {
            $docenteRole = DB::table('roles')->where('name', 'docente')->first();
            if ($docenteRole) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $docenteRole->id,
                    'model_type' => 'App\Models\User',
                    'model_id' => $docente2->id,
                ]);
            }
        }
    }
}