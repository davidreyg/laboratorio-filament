<?php

namespace Database\Seeders;

use App\Enums\Categoria\TipoCategoriaEnum;
use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categoria::create(['nombre' => 'HEMATOLOGÍA', 'tipo' => TipoCategoriaEnum::EXAMEN]);
        Categoria::create(['nombre' => 'BIOQUÍMICA', 'tipo' => TipoCategoriaEnum::EXAMEN]);
        Categoria::create(['nombre' => 'INMUNOLOGÍA', 'tipo' => TipoCategoriaEnum::EXAMEN]);
        Categoria::create(['nombre' => 'MICROBIOLOGÍA', 'tipo' => TipoCategoriaEnum::EXAMEN]);
        Categoria::create(['nombre' => 'Autoinmunes e Infecciosas', 'tipo' => TipoCategoriaEnum::EXAMEN]);
        Categoria::create(['nombre' => 'EXAMEN ORINA', 'tipo' => TipoCategoriaEnum::ITEM]);
        Categoria::create(['nombre' => 'EXAMEN QUIMICO', 'tipo' => TipoCategoriaEnum::ITEM]);
        Categoria::create(['nombre' => 'SEDIMENTO URINARIO', 'tipo' => TipoCategoriaEnum::ITEM]);
        Categoria::create(['nombre' => 'HEMOGRAMA', 'tipo' => TipoCategoriaEnum::ITEM]);
        Categoria::create(['nombre' => 'FÓRMULA MANUAL', 'tipo' => TipoCategoriaEnum::ITEM]);
        Categoria::create(['nombre' => 'CONCENTRACIÓN DE PROTEÍNAS', 'tipo' => TipoCategoriaEnum::ITEM]);
    }
}
