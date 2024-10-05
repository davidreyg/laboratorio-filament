<?php
namespace App\States\Orden;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

/**
 * @extends State<\App\Models\Orden>
 */
abstract class OrdenState extends State
{
    abstract public function color(): string;
    abstract public function display(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pendiente::class)
            ->allowTransition(Pendiente::class, Registrado::class)
            ->allowTransition(Registrado::class, Verificado::class)
        ;
    }

    public function transitionableStatesFormatted(): array
    {
        return collect($this->transitionableStates())
            ->mapWithKeys(function ($state) {
                $stateClass = 'App\\States\\Orden\\' . ucfirst($state);
                return [$state => (new $stateClass($this))->display()];
            })
            ->toArray();
    }
}
