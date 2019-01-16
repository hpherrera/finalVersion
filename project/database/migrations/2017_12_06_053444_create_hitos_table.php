<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHitosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hitos', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->string('nombre');
            $table->timestamp('fecha_inicio')->nullable()->default(null);
            $table->timestamp('fecha_termino')->nullable()->default(null);
            $table->bigInteger('proyecto_id')->index();
            $table->bigInteger('progreso');
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
        Schema::dropIfExists('hitos');
    }
}
