<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;
    protected $table = 'materia';
    protected $primaryKey = 'sigla';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sigla',
        'nombre',
        'semestre',
        'id_categoria'
    ];

    // Relación con Categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    // Relación con Grupos (a través de grupo_materia)
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_materia', 'sigla_materia', 'id_grupo')
                    ->withPivot('id_gestion')
                    ->withTimestamps();
    }
}