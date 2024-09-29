<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RespuestaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \DB::insert('insert into respuestas (nombre) values (?)', ['HAY REACCION']);
        \DB::insert('insert into respuestas (nombre) values (?)', ['NO SE OBSERVA REACCION']);
        \DB::insert('insert into respuestas (nombre) values (?)', ['POSITIVO']);
        \DB::insert('insert into respuestas (nombre) values (?)', ['NEGATIVO']);
    }
}
