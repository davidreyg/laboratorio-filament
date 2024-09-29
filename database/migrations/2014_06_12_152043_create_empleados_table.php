<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_documento')->unsigned()->unique();
            $table->string('nombres', 100);
            $table->string('apellido_paterno', 100);
            $table->string('apellido_materno', 100);
            $table->date('fecha_nacimiento');
            $table->date('fecha_alta');
            $table->char('sexo', 1);
            $table->tinyInteger('plaza')->unsigned();
            $table->string('viene_de', 100);
            $table->string('email', 100)->nullable();
            $table->string('telefono', 100)->nullable();

            $table->foreignId('establecimiento_id')->constrained();
            $table->foreignId('cargo_id')->constrained();
            $table->bigInteger('unidad_organica_id');
            $table->bigInteger('tipo_planilla_id');
            $table->bigInteger('condicion_id');
            $table->bigInteger('desplazamiento_id');
            $table->bigInteger('regimen_laboral_id');
            $table->bigInteger('funcion_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
