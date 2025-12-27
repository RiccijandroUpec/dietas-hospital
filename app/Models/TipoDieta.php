<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDieta extends Model
{
    use HasFactory;

    protected $table = 'tipos_dieta';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function subtipos()
    {
        return $this->hasMany(SubtipoDieta::class, 'tipo_dieta_id');
    }

    public function dietas()
    {
        return $this->hasMany(Dieta::class, 'tipo_dieta_id');
    }
}
