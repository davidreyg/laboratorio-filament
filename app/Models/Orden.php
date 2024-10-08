<?php

namespace App\Models;

use App\States\Orden\OrdenState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;

class Orden extends Model
{
    use HasFactory;
    use HasStates;

    public $timestamps = false;
    protected $fillable = [
        'diagnostico',
        'CI10',
        'CPN',
        'EG',
        'codigo_atencion',
        'numero_orden',
        'fecha_registro',
        'medico',
        'paciente_id',
        'establecimiento_id',
        'establecimiento_otro',
        'user_id',
        'registrador_id',
        'verificador_id',
        'estado',
        'observaciones',
    ];

    protected $hidden = [

    ];

    protected $casts = [
        'estado' => OrdenState::class,
    ];

    public function examens()
    {
        return $this->belongsToMany(Examen::class)
            ->withPivot([
                'resultado',
                'fecha_resultado',
                'unidad_id',
                'is_canceled',
                'motivo',
                'respuesta_id',
            ])
            ->using(ExamenOrden::class);
    }

    public function ordenExamens()
    {
        return $this->hasMany(ExamenOrden::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registrador()
    {
        return $this->belongsTo(User::class, 'registrador_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function getEstadoDetalleAttribute()
    {
        return config('appSection-orden.estados')[$this->estado];
    }
    // CALCULO DE SUBTOTAL Y TOTAL
    public function getTotal()
    {
        $subTotal = 0;
        $IGV = 0.18;
        $total = 0;

        // Sumamos el precio de cada examen asociado a la orden
        foreach ($this->examens as $examen) {
            $subTotal += $examen->precio;
            $total += $examen->precio;
        }

        return [
            'subTotal' => $subTotal,
            'IGV' => $IGV,
            'total' => $total,
        ];
    }
}
