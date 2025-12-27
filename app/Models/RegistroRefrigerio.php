<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroRefrigerio extends Model
{
    protected $fillable = [
        'paciente_id',
        'refrigerio_id',
        'fecha',
        'momento',
        'observacion',
        'created_by',
        'updated_by',
    ];

    public function paciente()
    {
        return $this->belongsTo(\App\Models\Paciente::class);
    }

    public function refrigerio()
    {
        return $this->belongsTo(\App\Models\Refrigerio::class);
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
