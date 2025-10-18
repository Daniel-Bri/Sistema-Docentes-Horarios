<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void
    {
        Schema::create('docentes', function (Blueprint $table) {
            $table->id();
            
            // Información personal
            $table->string('codigo')->unique()->comment('Código único del docente');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->string('especialidad');
            
            // Información académica
            $table->enum('grado_academico', [
                'Licenciatura', 
                'Maestría', 
                'Doctorado', 
                'Especialización'
            ])->default('Licenciatura');
            
            $table->integer('carga_horaria_maxima')->default(40)->comment('Horas máximas semanales');
            $table->integer('experiencia_anios')->default(0);
            
            // Estado y timestamps
            $table->boolean('activo')->default(true);
            $table->text('observaciones')->nullable();
            
            $table->timestamps(); // created_at y updated_at
            $table->softDeletes(); // deleted_at para eliminación suave
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
