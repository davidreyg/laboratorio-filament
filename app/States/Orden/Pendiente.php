<?php
namespace App\States\Orden;

class Pendiente extends OrdenState
{
    public static $name = 'pendiente';

    public function color(): string
    {
        return 'info';
    }

    public function display(): string
    {
        return 'Pendiente';
    }

    public function icon(): string
    {
        return 'tabler-arrow-forward-up';
    }
}
