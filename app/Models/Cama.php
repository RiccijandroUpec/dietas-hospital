<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\AuditService;

class Cama extends Model
{
    use HasFactory;

    protected $fillable = ['codigo', 'servicio_id'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            AuditService::log('create', "Cama creada: {$model->codigo}", 'Cama', $model->id);
        });

        static::updated(function ($model) {
            AuditService::log('update', "Cama actualizada: {$model->codigo}", 'Cama', $model->id);
        });

        static::deleted(function ($model) {
            AuditService::log('delete', "Cama eliminada: {$model->codigo}", 'Cama', $model->id);
        });
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }
}
