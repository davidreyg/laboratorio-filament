<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    // use IsEstablecimientoOwned;

    protected $fillable = [
        'numero_documento',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'fecha_alta',
        'sexo',
        'plaza',
        'viene_de',
        'email',
        'telefono',
        'establecimiento_id',
        // 'unidad_organica_id',
        'cargo_id',
        // 'tipo_planilla_id',
        // 'condicion_id',
        // 'desplazamiento_id',
        // 'regimen_laboral_id',
        // 'funcion_id',
    ];

    public function getNombreCompletoAttribute(): string
    {
        return "$this->apellido_paterno $this->apellido_materno, $this->nombres";
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

}
