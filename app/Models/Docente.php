<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Docente extends Model
{
    protected $table = 'docente';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'codigo',
        'fecha_contrato',
        'sueldo',
        'telefono',
        'id_users'
    ];

    // Convertir fecha_contrato automáticamente a Carbon
    protected function fechaContrato(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => \Carbon\Carbon::parse($value),
            set: fn ($value) => $value,
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function carreras()
    {
        // USAR LOS NOMBRES CORRECTOS DE LAS COLUMNAS
        return $this->belongsToMany(
            Carrera::class, 
            'docente_carrera', 
            'codigo_docente',    // Cambiado a 'codigo_docente'
            'id_carrera',        // Cambiado a 'id_carrera'
            'codigo',            // Llave primaria de docente
            'id'                 // Llave primaria de carrera
        );
    }
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'codigo_docente', 'codigo');
    }

    // Relación con Materias a través de carreras
    public function materias()
    {
        return $this->hasManyThrough(Materia::class, DocenteCarrera::class, 'docente_codigo', 'carrera_id', 'codigo', 'carrera_id');
    }

    // Relación con GruposMateria (grupos asignados)
    public function gruposMateria()
    {
        return $this->hasMany(GrupoMateria::class, 'docente_codigo', 'codigo');
    }
}