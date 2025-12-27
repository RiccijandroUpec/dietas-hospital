<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refrigerio extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function registros()
    {
        return $this->hasMany(RegistroRefrigerio::class);
    }
}
