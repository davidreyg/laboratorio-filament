<?php
namespace App\States\Orden;

class Registrado extends OrdenState
{
    public static $name = 'registrado';

    public function color(): string
    {
        return 'info';
    }

    public function display(): string
    {
        return 'Registrado';
    }
}
