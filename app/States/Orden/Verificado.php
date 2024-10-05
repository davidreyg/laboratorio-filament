<?php
namespace App\States\Orden;

class Verificado extends OrdenState
{
    public static $name = 'verificado';

    public function color(): string
    {
        return 'info';
    }

    public function display(): string
    {
        return 'Verificado';
    }
}
