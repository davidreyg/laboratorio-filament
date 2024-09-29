<?php

namespace Database\Seeders;

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
        Categoria::create(['nombre' => 'HEMATOLOGÍA']);
        Categoria::create(['nombre' => 'BIOQUÍMICA']);
        Categoria::create(['nombre' => 'INMUNOLOGÍA']);
        Categoria::create(['nombre' => 'MICROBIOLOGÍA']);
    }
}
