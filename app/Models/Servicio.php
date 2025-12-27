<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion'];

    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }

    public function camas()
    {
        return $this->hasMany(Cama::class);
    }
}
