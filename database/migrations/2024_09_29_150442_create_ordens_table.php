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
        Schema::create('ordens', function (Blueprint $table) {
            $table->id();
            $table->string('diagnostico', 255);
            $table->string('CI10', 255)->nullable();
            $table->string('CPN', 255)->nullable();
            $table->string('EG', 255)->nullable();
            $table->string('codigo_atencion', 255);
            $table->integer('numero_orden')->unsigned();
            $table->date('fecha_registro');
            $table->foreignId('paciente_id')->constrained();
            $table->foreignId('establecimiento_id')->nullable()->constrained();
            // $table->foreignId('empleado_id')->constrained();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->uuid('registrador_id')->nullable();
            $table->foreign('registrador_id')->references('id')->on('users');
            $table->uuid('verificador_id')->nullable();
            $table->foreign('verificador_id')->references('id')->on('users');
            $table->string('establecimiento_otro', 255)->nullable();
            $table->string('medico', 100);
            $table->string('estado');
            $table->string('observaciones', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordens');
    }
};
