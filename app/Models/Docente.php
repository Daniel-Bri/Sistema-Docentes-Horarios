<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;
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

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    // Relación con Carreras (N:M)
    public function carreras()
    {
        return $this->belongsToMany(Carrera::class, 'docente_carrera', 'codigo_docente', 'id_carrera');
    }

    // Relación con Asistencias
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'codigo_docente');
    }
}