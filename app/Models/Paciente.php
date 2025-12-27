<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'estado',
        'edad',
        'condicion',
        'servicio_id',
        'cama_id',
        'created_by',
        'updated_by',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function cama()
    {
        return $this->belongsTo(Cama::class);
    }

    public function registrosRefrigerio()
    {
        return $this->hasMany(RegistroRefrigerio::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}
