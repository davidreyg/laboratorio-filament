<?php

namespace App\Models;

use App\Models\Ubigeo\Distrito;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Establecimiento extends Model
{
    use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'codigo',
        'direccion',
        'categoria',
        'telefono',
        'ris',
        'tipo',
        'correo',
        'parent_id',
        'distrito_id',
    ];
    protected $casts = [
        'has_lab' => 'boolean'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public static function obtenerPadre(string|null $establecimiento): string|null
    {
        $padre = null;
        switch ($establecimiento) {
            case 'ESTABLECIMIENTO':
                $padre = 'RIS';
                break;
            case 'RIS':
                $padre = 'DIRIS';
                break;
            case 'DIRIS':
                // null;
                break;
            default:
                //null
                break;
        }
        return $padre;
    }


}
