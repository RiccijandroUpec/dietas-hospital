<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\AuditService;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'prefijo', 'descripcion'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            AuditService::log('create', "Servicio creado: {$model->nombre}", 'Servicio', $model->id);
        });

        static::updated(function ($model) {
            AuditService::log('update', "Servicio actualizado: {$model->nombre}", 'Servicio', $model->id);
        });

        static::deleted(function ($model) {
            AuditService::log('delete', "Servicio eliminado: {$model->nombre}", 'Servicio', $model->id);
        });
    }

    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }

    public function camas()
    {
        return $this->hasMany(Cama::class);
    }
}
