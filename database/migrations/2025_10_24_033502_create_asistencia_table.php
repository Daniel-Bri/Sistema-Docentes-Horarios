<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('hora_registro');
            $table->enum('estado', ['presente', 'ausente', 'justificado'])->default('presente');
            $table->string('codigo_docente');
            $table->foreign('codigo_docente')->references('codigo')->on('docente')->onDelete('cascade');
            $table->foreignId('id_grupo_materia')->constrained('grupo_materia')->onDelete('cascade');
            $table->foreignId('id_horario')->constrained('horario')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['fecha', 'codigo_docente', 'id_grupo_materia', 'id_horario']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('asistencia');
    }
};