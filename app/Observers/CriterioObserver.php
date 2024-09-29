<?php

namespace App\Observers;

use App\Enums\Criterio\TipoCriterioEnum;
use App\Models\Criterio;

class CriterioObserver
{
    /**
     * Handle the Criterio "created" event.
     */
    public function created(Criterio $criterio): void
    {
        //
    }

    /**
     * Handle the Criterio "updated" event.
     */
    public function saved(Criterio $criterio): void
    {
        if ($criterio->tipo === TipoCriterioEnum::RANGO) {
            $criterio->exacto = null;
        } else {
            $criterio->minimo = null;
            $criterio->maximo = null;
        }
    }

    /**
     * Handle the Criterio "deleted" event.
     */
    public function deleted(Criterio $criterio): void
    {
        //
    }

    /**
     * Handle the Criterio "restored" event.
     */
    public function restored(Criterio $criterio): void
    {
        //
    }

    /**
     * Handle the Criterio "force deleted" event.
     */
    public function forceDeleted(Criterio $criterio): void
    {
        //
    }
}
