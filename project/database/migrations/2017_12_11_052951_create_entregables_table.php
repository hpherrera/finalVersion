<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntregablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entregables', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->string('nombre');
            $table->timestamp('fecha')->nullable()->default(null);
            $table->bigInteger('tarea_id')->index();
            $table->bigInteger('estadoEntregable_id')->index();
            $table->string('ruta');
            $table->bigInteger('id_padre')->index();
            $table->bigInteger('subidoPor')->index();
            $table->bigInteger('tipo')->index();
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
        Schema::dropIfExists('entregables');
    }
}
