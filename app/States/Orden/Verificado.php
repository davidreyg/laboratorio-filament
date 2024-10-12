<?php
namespace App\States\Orden;

class Verificado extends OrdenState
{
    public static $name = 'verificado';

    public function color(): string
    {
        return 'success';
    }

    public function display(): string
    {
        return 'Verificado';
    }

    public function action(): string
    {
        return 'Verificar';
    }

    public function icon(): string
    {
        return 'tabler-check';
    }
}
