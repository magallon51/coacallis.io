<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER after_reservacion_insert
        AFTER INSERT ON reservaciones
        FOR EACH ROW
        BEGIN
            -- Obtener el tipo de habitación seleccionado
            DECLARE tipo_habitacion VARCHAR(255);
            SELECT tipo_habitacion INTO tipo_habitacion
            FROM asigna_habitacion
            WHERE id_asigna = NEW.id_asigna;

            -- Verificar si hay suficientes habitaciones disponibles
            IF (SELECT cantidad_habitacion FROM asigna_habitacion WHERE id_asigna = NEW.id_asigna) <= 0 THEN
                SIGNAL SQLSTATE "45000"
                SET MESSAGE_TEXT = "No hay suficientes habitaciones disponibles para realizar la reservación";
            END IF;

            -- Actualizar la cantidad de habitaciones disponibles en el hotel
            UPDATE asigna_habitacion
            SET cantidad_habitacion = cantidad_habitacion - 1
            WHERE id_asigna = NEW.id_asigna;

            -- Verificar si la fecha_fin ha pasado
            IF NEW.fecha_fin < CURDATE() THEN
                -- Incrementar la cantidad de habitaciones disponibles en 1
                UPDATE asigna_habitacion
                SET cantidad_habitacion = cantidad_habitacion + 1
                WHERE id_asigna = NEW.id_asigna;
            END IF;

            -- Agregar lógica adicional según sea necesario
        END;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trigger_after_reservacion_insert');
    }
};
