<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cama extends Model
{
    use HasFactory;

    protected $fillable = ['codigo', 'servicio_id'];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }
}
