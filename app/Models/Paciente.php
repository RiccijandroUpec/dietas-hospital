<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\AuditService;

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

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            AuditService::log('create', "Paciente creado: {$model->nombre} {$model->apellido}", 'Paciente', $model->id);
        });

        static::updated(function ($model) {
            AuditService::log('update', "Paciente actualizado: {$model->nombre} {$model->apellido}", 'Paciente', $model->id);
        });

        static::deleted(function ($model) {
            AuditService::log('delete', "Paciente eliminado: {$model->nombre} {$model->apellido}", 'Paciente', $model->id);
        });
    }

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
