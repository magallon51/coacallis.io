<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservaciones', function (Blueprint $table) 
        {
            $table->id('id_reservacion'); 
            $table->string('nombre');
            $table->string('ap');
            $table->string('am');
            $table->string('correo');
            $table->integer('cant_a');
            $table->integer('cant_n');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');

            $table->unsignedBigInteger('id_hotel');
            $table->foreign('id_hotel')->references('id_hotel')->on('hoteles')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('id_asigna');
            $table->foreign('id_asigna')->references('id_asigna')->on('asigna_habitacion');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservaciones');
    }
};