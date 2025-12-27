<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dieta extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_dieta_id',
        'nombre',
        'descripcion',
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoDieta::class, 'tipo_dieta_id');
    }

    public function subtipos()
    {
        return $this->belongsToMany(SubtipoDieta::class, 'dieta_subtipo_dieta');
    }

    public function registros()
    {
        return $this->belongsToMany(RegistroDieta::class, 'paciente_dietas', 'dieta_id', 'registro_dieta_id');
    }
}
