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
        DB::unprepared('
            CREATE TRIGGER after_update_reservacion
            AFTER UPDATE ON reservaciones FOR EACH ROW
            BEGIN
                IF NEW.fecha_fin < CURRENT_DATE THEN
                    -- Incrementa la cantidad de habitaciones disponibles
                    UPDATE asigna_habitacion
                    SET cantidad_habitacion = cantidad_habitacion + 1
                    WHERE id_asigna = NEW.id_asigna;
                END IF;
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
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_reservacion');
    }
};
