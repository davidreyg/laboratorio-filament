<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nombre'
    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    /**
     * A resource key to be used in the serialized responses.
     */
    protected string $resourceKey = 'Respuesta';

    public function examens()
    {
        return $this->belongsToMany(Examen::class)
            ->as('examen_respuesta');
    }
}