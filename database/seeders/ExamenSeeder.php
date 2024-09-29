<?php

namespace Database\Seeders;

use App\Enums\Examen\TipoExamenEnum;
use App\Enums\Unidad\TipoUnidadEnum;
use App\Models\Examen;
use App\Models\Respuesta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $sqlFile = base_path('database/sql/examens.sql');
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            \DB::unprepared($sql);
        }

        $numRespuestasAleatorias = 2; // Cambia el número según tus necesidades
        Examen::get()->each(function (Examen $examen) use ($numRespuestasAleatorias) {

            if ($examen->tipo === TipoExamenEnum::UNIDAD) {
                $examen->unidads()->sync([
                    1 => [
                        'minimo' => fake()->randomFloat(2, 1, 10),
                        'maximo' => fake()->randomFloat(2, 10, 50),
                        'tipo' => TipoUnidadEnum::MULTIVALOR
                    ],
                    2 => [
                        'minimo' => fake()->randomFloat(2, 100, 110),
                        'maximo' => fake()->randomFloat(2, 150, 200),
                        'tipo' => TipoUnidadEnum::MULTIVALOR
                    ],
                ]);
            } else if ($examen->tipo === TipoExamenEnum::RESPUESTA) {
                $respuestasAleatorias = Respuesta::inRandomOrder()->take($numRespuestasAleatorias)->get();
                $examen->respuestas()->sync($respuestasAleatorias);
            }

        });
    }
}
