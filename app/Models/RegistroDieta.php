<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\AuditService;

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
        'es_tardia',
        'created_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $paciente = $model->paciente;
            $nombre = $paciente ? "{$paciente->nombre} {$paciente->apellido}" : "ID {$model->paciente_id}";
            AuditService::log('create', "Registro de dieta creado para {$nombre}", 'RegistroDieta', $model->id);
        });

        static::updated(function ($model) {
            $paciente = $model->paciente;
            $nombre = $paciente ? "{$paciente->nombre} {$paciente->apellido}" : "ID {$model->paciente_id}";
            AuditService::log('update', "Registro de dieta actualizado para {$nombre}", 'RegistroDieta', $model->id);
        });

        static::deleted(function ($model) {
            $paciente = $model->paciente;
            $nombre = $paciente ? "{$paciente->nombre} {$paciente->apellido}" : "ID {$model->paciente_id}";
            AuditService::log('delete', "Registro de dieta eliminado para {$nombre}", 'RegistroDieta', $model->id);
        });
    }

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
