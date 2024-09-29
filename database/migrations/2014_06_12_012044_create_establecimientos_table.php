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
        Schema::create('establecimientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->integer('codigo')->unsigned()->unique();
            $table->string('direccion', 100)->nullable();
            $table->string('categoria', 4)->nullable();
            $table->string('ris', 60)->nullable();
            // $table->string('distrito', 60)->nullable();
            $table->string('correo', 60)->nullable();
            $table->integer('telefono')->unsigned()->nullable();
            $table->string('tipo', 30);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('distrito_id')->nullable();
            $table->foreign('parent_id')
                ->references('id')->on('establecimientos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establecimientos');
    }
};
