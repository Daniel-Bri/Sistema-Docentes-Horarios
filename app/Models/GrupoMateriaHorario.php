<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoMateriaHorario extends Model
{
    use HasFactory;

    protected $table = 'grupo_materia_horario';

    protected $fillable = [
        'id_grupo_materia',
        'id_horario', 
        'codigo_docente',
        'id_aula'
    ];

    // Relación con GrupoMateria
    public function grupoMateria()
    {
        return $this->belongsTo(GrupoMateria::class, 'id_grupo_materia');
    }

    // Relación con Horario
    public function horario()
    {
        return $this->belongsTo(Horario::class, 'id_horario');
    }

    // Relación con Docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'codigo_docente', 'codigo');
    }

    // Relación con Aula
    public function aula()
    {
        return $this->belongsTo(Aula::class, 'id_aula');
    }
}