<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoMateria extends Model
{
    use HasFactory;
    protected $table = 'grupo_materia';
    protected $fillable = [
        'id_gestion',
        'id_grupo',
        'sigla_materia'
    ];

    // Relación con GestionAcademica
    public function gestion()
    {
        return $this->belongsTo(GestionAcademica::class, 'id_gestion');
    }

    // Relación con Grupo
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo');
    }

    // Relación con Materia
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'sigla_materia', 'sigla');
    }

    // Relación con GrupoMateriaHorario
    public function grupoMateriaHorarios()
    {
        return $this->hasMany(GrupoMateriaHorario::class, 'id_grupo_materia');
    }

    // Relación con Asistencias
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_grupo_materia');
    }
}