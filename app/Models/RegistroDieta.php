<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroDieta extends Model
{
    use HasFactory;

    protected $table = 'registro_dietas';

    protected $fillable = [
        'paciente_id',
        'dieta_id',
        'tipo_comida',
        'vajilla',
        'fecha',
        'observaciones',
        'created_by',
        'updated_by',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function dietas()
    {
        return $this->belongsToMany(Dieta::class, 'dieta_registro_dieta');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function cama()
    {
        return $this->belongsTo(Cama::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
