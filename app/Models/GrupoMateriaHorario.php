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
        'id_aula',
        'estado_aula'
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

    // Relación con Aula
    public function aula()
    {
        return $this->belongsTo(Aula::class, 'id_aula');
    }

    // Relación con Asistencias
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_grupo_materia', 'id_grupo_materia');
    }
}