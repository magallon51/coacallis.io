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
        Schema::create('asigna_habitacion', function (Blueprint $table) {
            $table->id('id_asigna');
            $table->foreignId('id_hotel')->constrained('hoteles', 'id_hotel');
            $table->foreignId('id_habitacion')->constrained('habitaciones', 'id_habitacion');
            $table->integer('cantidad_habitacion');
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
        Schema::dropIfExists('asigna_habitacion');
    }
};
