<?php

namespace App\Models;

use App\Enums\Categoria\TipoCategoriaEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'tipo',
    ];

    protected $hidden = [

    ];

    protected $casts = [
        'tipo' => TipoCategoriaEnum::class
    ];

    /**
     * A resource key to be used in the serialized responses.
     */
    protected string $resourceKey = 'Categoria';
}
