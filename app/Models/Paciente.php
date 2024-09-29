<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'numero_documento',
        'fecha_nacimiento',
        'edad',
        'sexo',
        'direccion',
        'telefono',
        'historia_clinica',
        'tipo_documento_id',
        'tipo_persona_id',
    ];


    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function getNombreCompletoAttribute()
    {
        return $this->nombres . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno;
    }

    public function tipo_documento()
    {
        return $this->belongsTo(TipoDocumento::class);
    }

    // TODO: Falta diagnosticos
    // public function diagnosticos()
    // {
    //     return $this->hasMany(Diagnostico::class);
    // }
}
