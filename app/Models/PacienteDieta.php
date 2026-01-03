<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\AuditService;

class PacienteDieta extends Model
{
    use HasFactory;

    protected $table = 'paciente_dietas';

    protected $fillable = [
        'paciente_id',
        'dieta_id',
        'created_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $paciente = $model->paciente;
            $dieta = $model->dieta;
            $pNombre = $paciente ? "{$paciente->nombre} {$paciente->apellido}" : "ID {$model->paciente_id}";
            $dNombre = $dieta ? $dieta->nombre : "ID {$model->dieta_id}";
            AuditService::log('create', "Dieta {$dNombre} asignada a paciente {$pNombre}", 'PacienteDieta', $model->id);
        });

        static::updated(function ($model) {
            $paciente = $model->paciente;
            $dieta = $model->dieta;
            $pNombre = $paciente ? "{$paciente->nombre} {$paciente->apellido}" : "ID {$model->paciente_id}";
            $dNombre = $dieta ? $dieta->nombre : "ID {$model->dieta_id}";
            AuditService::log('update', "Asignación actualizada: Dieta {$dNombre} - Paciente {$pNombre}", 'PacienteDieta', $model->id);
        });

        static::deleted(function ($model) {
            $paciente = $model->paciente;
            $dieta = $model->dieta;
            $pNombre = $paciente ? "{$paciente->nombre} {$paciente->apellido}" : "ID {$model->paciente_id}";
            $dNombre = $dieta ? $dieta->nombre : "ID {$model->dieta_id}";
            AuditService::log('delete', "Dieta {$dNombre} desasignada del paciente {$pNombre}", 'PacienteDieta', $model->id);
        });
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function dieta()
    {
        return $this->belongsTo(Dieta::class);
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
