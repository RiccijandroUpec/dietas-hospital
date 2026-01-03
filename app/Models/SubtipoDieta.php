<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\AuditService;

class SubtipoDieta extends Model
{
    use HasFactory;

    protected $table = 'subtipos_dieta';

    protected $fillable = [
        'tipo_dieta_id',
        'nombre',
        'descripcion',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            AuditService::log('create', "Subtipo de dieta creado: {$model->nombre}", 'SubtipoDieta', $model->id);
        });

        static::updated(function ($model) {
            AuditService::log('update', "Subtipo de dieta actualizado: {$model->nombre}", 'SubtipoDieta', $model->id);
        });

        static::deleted(function ($model) {
            AuditService::log('delete', "Subtipo de dieta eliminado: {$model->nombre}", 'SubtipoDieta', $model->id);
        });
    }

    public function tipo()
    {
        return $this->belongsTo(TipoDieta::class, 'tipo_dieta_id');
    }

    public function dietas()
    {
        return $this->belongsToMany(Dieta::class, 'dieta_subtipo_dieta');
    }
}
