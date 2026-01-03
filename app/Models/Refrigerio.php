<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\AuditService;

class Refrigerio extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            AuditService::log('create', "Refrigerio creado: {$model->nombre}", 'Refrigerio', $model->id);
        });

        static::updated(function ($model) {
            AuditService::log('update', "Refrigerio actualizado: {$model->nombre}", 'Refrigerio', $model->id);
        });

        static::deleted(function ($model) {
            AuditService::log('delete', "Refrigerio eliminado: {$model->nombre}", 'Refrigerio', $model->id);
        });
    }

    public function registros()
    {
        return $this->hasMany(RegistroRefrigerio::class);
    }
}
