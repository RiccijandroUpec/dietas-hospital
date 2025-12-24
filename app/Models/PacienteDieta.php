<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
