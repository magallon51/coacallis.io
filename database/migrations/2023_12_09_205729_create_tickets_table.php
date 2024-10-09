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
        Schema::create('tickets', function (Blueprint $table)
        {
            $table->id('id_ticket');
            $table->date('fecha_pago');
            $table->decimal('precio_total', 10, 2);
            $table->unsignedBigInteger('id_reservacion');
            $table->foreign('id_reservacion')->references('id_reservacion')->on('reservaciones')->onDelete('cascade');
            $table->unsignedBigInteger('id_hotel');
            $table->foreign('id_hotel')->references('id_hotel')->on('hoteles')->onDelete('cascade');
            $table->unsignedBigInteger('id_tarjeta');
            $table->foreign('id_tarjeta')->references('id_tarjeta')->on('tarjetas')->onDelete('cascade');
            $table->unsignedBigInteger('user_id'); // Esta línea agrega la referencia a users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('pdf_path')->nullable();
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
        Schema::dropIfExists('tickets');
    }
};
