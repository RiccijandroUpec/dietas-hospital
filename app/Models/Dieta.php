<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\AuditService;

class Dieta extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_dieta_id',
        'nombre',
        'descripcion',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            AuditService::log('create', "Dieta creada: {$model->nombre}", 'Dieta', $model->id);
        });

        static::updated(function ($model) {
            AuditService::log('update', "Dieta actualizada: {$model->nombre}", 'Dieta', $model->id);
        });

        static::deleted(function ($model) {
            AuditService::log('delete', "Dieta eliminada: {$model->nombre}", 'Dieta', $model->id);
        });
    }

    public function tipo()
    {
        return $this->belongsTo(TipoDieta::class, 'tipo_dieta_id');
    }

    public function subtipos()
    {
        return $this->belongsToMany(SubtipoDieta::class, 'dieta_subtipo_dieta');
    }

    public function registros()
    {
        return $this->belongsToMany(RegistroDieta::class, 'paciente_dietas', 'dieta_id', 'registro_dieta_id');
    }
}
