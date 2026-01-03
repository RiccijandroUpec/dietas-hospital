<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\AuditService;

class TipoDieta extends Model
{
    use HasFactory;

    protected $table = 'tipos_dieta';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            AuditService::log('create', "Tipo de dieta creado: {$model->nombre}", 'TipoDieta', $model->id);
        });

        static::updated(function ($model) {
            AuditService::log('update', "Tipo de dieta actualizado: {$model->nombre}", 'TipoDieta', $model->id);
        });

        static::deleted(function ($model) {
            AuditService::log('delete', "Tipo de dieta eliminado: {$model->nombre}", 'TipoDieta', $model->id);
        });
    }

    public function subtipos()
    {
        return $this->hasMany(SubtipoDieta::class, 'tipo_dieta_id');
    }

    public function dietas()
    {
        return $this->hasMany(Dieta::class, 'tipo_dieta_id');
    }
}
