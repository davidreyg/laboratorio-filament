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
        $this->sedePrincipal();
        $this->tipoDocumentos();
        $establecimientos = base_path('database/sql/establecimientos.sql');
        $cargos = base_path('database/sql/cargos.sql');
        $empleados = base_path('database/sql/empleados.sql');

        if (file_exists($establecimientos)) {
            $sql = file_get_contents($establecimientos);
            \DB::unprepared($sql);
        }
        if (file_exists($cargos)) {
            $sql = file_get_contents($cargos);
            \DB::unprepared($sql);
        }
        if (file_exists($empleados)) {
            $sql = file_get_contents($empleados);
            \DB::unprepared($sql);
        }
    }

    public function tipoDocumentos()
    {
        \DB::table('tipo_documentos')->insert(['nombre' => 'DNI', 'digitos' => 8]);
        \DB::table('tipo_documentos')->insert(['nombre' => 'Carné de extranjeria', 'digitos' => 11]);
        \DB::table('tipo_documentos')->insert(['nombre' => 'RUC', 'digitos' => 14]);
    }

    public function sedePrincipal()
    {
        $data = [
            'nombre' => 'DIRIS SEDE ADMINISTRATIVA',
            'codigo' => 99999999,
            'direccion' => 'Calle Los Pepitos S/N',
            'telefono' => 955927839,
            'ris' => 'LIMA',
            'tipo' => 'DIRIS',
            'parent_id' => null
        ];

        \DB::table('establecimientos')->insert($data);
    }
}
