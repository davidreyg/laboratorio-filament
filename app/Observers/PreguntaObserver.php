<?php

namespace App\Observers;

use App\Enums\Criterio\TipoCriterioEnum;
use App\Enums\Grupo\TipoPreguntaEnum;
use App\Models\Item;
use App\Models\Opcion;
use App\Models\Pregunta;

class PreguntaObserver
{
    /**
     * Handle the Pregunta "created" event.
     */
    public function created(Pregunta $pregunta): void
    {
        //
    }

    /**
     * Handle the Pregunta "updated" event.
     */
    public function saved(Pregunta $pregunta): void
    {
        if ($pregunta->tipo === TipoPreguntaEnum::SIMPLE) {
            // $pregunta->items()->delete();
            $pregunta->opcions()->each(function (Opcion $opcion) use ($pregunta) {
                $item = $pregunta->items()->create(['nombre' => $opcion->nombre]);
                $opcion->criterio()->updateOrCreate(['item_id' => $item->id], ['tipo' => TipoCriterioEnum::ID]);
                // $opcion->criterio()->create([
                //     'item_id' => 'integer',
                //     // 'opcion_id' => 'integer',
                //     'tipo' => TipoCriterioEnum::ID,
                // ]);
                // $pregunta->items()->each(function(Item $item){
                //     $item->criterio->
                // })
            });
        }
    }

    /**
     * Handle the Pregunta "deleted" event.
     */
    public function deleted(Pregunta $pregunta): void
    {
        //
    }

    /**
     * Handle the Pregunta "restored" event.
     */
    public function restored(Pregunta $pregunta): void
    {
        //
    }

    /**
     * Handle the Pregunta "force deleted" event.
     */
    public function forceDeleted(Pregunta $pregunta): void
    {
        //
    }
}
