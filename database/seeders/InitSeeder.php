<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $establecimientos = base_path('database/sql/establecimientos.sql');
        $opciones = base_path('database/sql/opciones.sql');

        if (file_exists($establecimientos)) {
            $sql = file_get_contents($establecimientos);
            \DB::unprepared($sql);
        }
        if (file_exists($opciones)) {
            $sql = file_get_contents($opciones);
            \DB::unprepared($sql);
        }
    }
}
