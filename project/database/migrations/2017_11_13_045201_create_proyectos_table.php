<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->string('titulo');
            $table->bigInteger('estudiante_id')->index();
            $table->bigInteger('tipo_id')->index();
            $table->bigInteger('estado_id')->index();
            $table->Integer('progreso');
            $table->bigInteger('area_id')->index();
            $table->bigInteger('profesorGuia_id')->index();
            $table->bigInteger('year')->index();
            $table->bigInteger('semestre')->index();
            $table->string('nombre_estudiante');
            $table->bigInteger('curso_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proyectos');
    }
}