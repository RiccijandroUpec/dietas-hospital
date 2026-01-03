<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\AuditService;

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

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $paciente = $model->paciente;
            $nombre = $paciente ? "{$paciente->nombre} {$paciente->apellido}" : "ID {$model->paciente_id}";
            AuditService::log('create', "Registro de refrigerio creado para {$nombre}", 'RegistroRefrigerio', $model->id);
        });

        static::updated(function ($model) {
            $paciente = $model->paciente;
            $nombre = $paciente ? "{$paciente->nombre} {$paciente->apellido}" : "ID {$model->paciente_id}";
            AuditService::log('update', "Registro de refrigerio actualizado para {$nombre}", 'RegistroRefrigerio', $model->id);
        });

        static::deleted(function ($model) {
            $paciente = $model->paciente;
            $nombre = $paciente ? "{$paciente->nombre} {$paciente->apellido}" : "ID {$model->paciente_id}";
            AuditService::log('delete', "Registro de refrigerio eliminado para {$nombre}", 'RegistroRefrigerio', $model->id);
        });
    }

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
