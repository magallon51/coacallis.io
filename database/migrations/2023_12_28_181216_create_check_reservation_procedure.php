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
        // Eliminar el procedimiento almacenado si ya existe
        DB::unprepared('DROP PROCEDURE IF EXISTS check_reservation_procedure');

        // Crear el nuevo procedimiento almacenado
        DB::unprepared('
        CREATE PROCEDURE check_reservation_procedure(
            IN p_fecha_inicio DATE,
            IN p_fecha_fin DATE,
            IN p_id_asigna INT,
            OUT p_permitir_reservacion INT
        )
        BEGIN
            DECLARE habitacion_reservada_existente INT;
        
            -- Verificar si hay reservaciones con las mismas fechas y tipo de habitación
            SELECT COUNT(*) INTO habitacion_reservada_existente
            FROM reservaciones
            WHERE id_asigna = p_id_asigna
                AND (
                    -- No permitir reservaciones si las fechas son iguales y tipo de habitación igual
                    (fecha_inicio = p_fecha_inicio AND fecha_fin = p_fecha_fin AND id_asigna = p_id_asigna)
                    OR
                    -- Permitir reservaciones si hay superposición de fechas pero tipo de habitación diferente
                    (
                        (fecha_inicio BETWEEN p_fecha_inicio AND p_fecha_fin
                            OR fecha_fin BETWEEN p_fecha_inicio AND p_fecha_fin
                            OR p_fecha_inicio BETWEEN fecha_inicio AND fecha_fin
                            OR p_fecha_fin BETWEEN fecha_inicio AND fecha_fin)
                        AND id_asigna != p_id_asigna
                    )
                );
        
            -- Ajusta las condiciones según tu lógica
            IF habitacion_reservada_existente > 0 THEN
                SET p_permitir_reservacion = 0;
            ELSE
                SET p_permitir_reservacion = 1;
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
        DB::unprepared('DROP PROCEDURE IF EXISTS check_reservation_procedure');
    }
};
