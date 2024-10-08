<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
    use HasFactory;
    // protected $with = ['categoria', 'unidads',, 'respuestas'];
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'codigo',
        'precio',
        'categoria_id',
        'is_active',
        'tipo',
        'parent_id',
    ];

    protected $hidden = [

    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * A resource key to be used in the serialized responses.
     */
    protected string $resourceKey = 'Examen';

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function unidads()
    {
        return $this->belongsToMany(related: Unidad::class)
            ->withPivot(columns: ['minimo', 'maximo', 'tipo', 'operador']);
    }

    public function respuestas()
    {
        return $this->belongsToMany(Respuesta::class)
            ->as('examen_respuesta');
    }

    public function ordens()
    {
        return $this->belongsToMany(Orden::class)
            ->withPivot([
                'resultado',
                'fecha_resultado',
                'unidad_id',
                'is_canceled',
                'motivo',
            ])
            ->using(ExamenOrden::class);
    }
}
