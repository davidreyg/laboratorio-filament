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

        $examen = Examen::create(
            [
                'codigo' => 200,
                'nombre' => 'Examen Completo de Orina',
                'precio' => '135',
                'categoria_id' => 1,
                'is_active' => 1,
                'tipo' => TipoExamenEnum::STRING,
            ]
        );
        // $seccion = Seccion::create(['nombre' => 'Microbiologia']);

        $examen2 = Examen::create(
            [
                'codigo' => 300,
                'nombre' => 'Hemoglobina completo',
                'precio' => '137',
                'categoria_id' => 1,
                'is_active' => 1,
                'tipo' => TipoExamenEnum::STRING,
            ]
        );
        Examen::create([
            'codigo' => 20001,
            'nombre' => 'PH',
            'categoria_id' => 1,
            'parent_id' => $examen->id,
            'tipo' => TipoExamenEnum::UNIDAD,
            'is_active' => 1,
        ]);
        Examen::create([
            'codigo' => 20002,
            'nombre' => 'Densidad',
            'categoria_id' => 1,
            'parent_id' => $examen->id,
            'tipo' => TipoExamenEnum::STRING,
            'is_active' => 1,
        ]);
        Examen::create([
            'codigo' => 20003,
            'nombre' => 'Color',
            'categoria_id' => 1,
            'parent_id' => $examen->id,
            'tipo' => TipoExamenEnum::RESPUESTA,
            'is_active' => 1,
        ]);
        Examen::create([
            'codigo' => 20004,
            'nombre' => 'Aspecto',
            'categoria_id' => 1,
            'parent_id' => $examen->id,
            'tipo' => TipoExamenEnum::STRING,
            'is_active' => 1,
        ]);

        Examen::create([
            'codigo' => 30001,
            'nombre' => 'No se',
            'categoria_id' => 1,
            'parent_id' => $examen2->id,
            'tipo' => TipoExamenEnum::RESPUESTA,
            'is_active' => 1,
        ]);
        Examen::create([
            'codigo' => 30002,
            'nombre' => 'Ph',
            'categoria_id' => 1,
            'parent_id' => $examen2->id,
            'tipo' => TipoExamenEnum::UNIDAD,
            'is_active' => 1,
        ]);

        $numRespuestasAleatorias = 2; // Cambia el número según tus necesidades
        Examen::get()->each(function (Examen $examen) use ($numRespuestasAleatorias) {

            if ($examen->tipo === TipoExamenEnum::UNIDAD->value) {
                $examen->unidads()->sync([
                    1 => [
                        'minimo' => fake()->randomFloat(2, 1, 10),
                        'maximo' => fake()->randomFloat(2, 10, 50),
                        'tipo' => TipoUnidadEnum::MULTIVALOR->value
                    ],
                    2 => [
                        'minimo' => fake()->randomFloat(2, 100, 110),
                        'maximo' => fake()->randomFloat(2, 150, 200),
                        'tipo' => TipoUnidadEnum::MULTIVALOR->value
                    ],
                ]);
            } else if ($examen->tipo === TipoExamenEnum::RESPUESTA->value) {
                $respuestasAleatorias = Respuesta::inRandomOrder()->take($numRespuestasAleatorias)->get();
                $examen->respuestas()->sync($respuestasAleatorias);
            }

        });
    }
}
