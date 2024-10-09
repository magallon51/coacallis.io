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
        Schema::create('hoteles', function (Blueprint $table) 
        {
            $table->id('id_hotel');
            $table->string('nombre');
            $table->string('caracteristica');
            $table->string('servicio');
            $table->double('precio_noche');
            $table->string('telefono', 10)->unique();
            
            // Asegúrate de que la columna de clave externa tenga el mismo tipo
            $table->unsignedBigInteger('id_ubicacion');

            // Añade la restricción de clave externa
            $table->foreign('id_ubicacion')->references('id_ubicacion')->on('ubicaciones')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hoteles');
    }
};
