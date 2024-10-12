<?php
namespace App\States\Orden;

class Registrado extends OrdenState
{
    public static $name = 'registrado';

    public function color(): string
    {
        return 'warning';
    }

    public function display(): string
    {
        return 'Registrado';
    }

    public function action(): string
    {
        return 'Registrar';
    }

    public function icon(): string
    {
        return 'tabler-arrow-forward-up';
    }
}
