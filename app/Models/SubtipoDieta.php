<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubtipoDieta extends Model
{
    use HasFactory;

    protected $table = 'subtipos_dieta';

    protected $fillable = [
        'tipo_dieta_id',
        'nombre',
        'descripcion',
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoDieta::class, 'tipo_dieta_id');
    }

    public function dietas()
    {
        return $this->belongsToMany(Dieta::class, 'dieta_subtipo_dieta');
    }
}
