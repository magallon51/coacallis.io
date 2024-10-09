<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!$this->procedureExists()) {
            $this->createProcedure();
        }
    }

    public function down()
    {
        $sql = "DROP PROCEDURE IF EXISTS calcularTotal";
        DB::unprepared($sql);
    }

    protected function procedureExists()
    {
        $checkSql = "SHOW PROCEDURE STATUS WHERE Db = ? AND Name = ?";
        $result = DB::select($checkSql, [config('database.connections.mysql.database'), 'calcularTotal']);
        return !empty($result);
    }

    protected function createProcedure()
    {
                $sql = "
                CREATE PROCEDURE calcularTotal(IN p_reservacion_id INT)
                BEGIN
                    DECLARE v_precio_noche DECIMAL(10, 2);
                    DECLARE v_tipo_habitacion VARCHAR(255);
                    DECLARE v_fecha_inicio DATE;
                    DECLARE v_fecha_fin DATE;
                    DECLARE v_total DECIMAL(10, 2);

                    -- Obtener información necesaria de la reservación
                    SELECT h.precio_noche, hab.tipo_habitacion, r.fecha_inicio, r.fecha_fin
                    INTO v_precio_noche, v_tipo_habitacion, v_fecha_inicio, v_fecha_fin
                    FROM reservaciones r
                    JOIN asigna_habitacion ah ON r.id_asigna = ah.id_asigna
                    JOIN hoteles h ON r.id_hotel = h.id_hotel
                    JOIN habitaciones hab ON ah.id_habitacion = hab.id_habitacion
                    WHERE r.id_reservacion = p_reservacion_id;

                    -- Ajustar precio solo si el tipo de habitación no es 'Individual'
                    IF v_tipo_habitacion <> 'Individual' THEN
                        -- Ajustar precio según el tipo de habitación
                        IF v_tipo_habitacion = 'Doble' THEN
                            SET v_precio_noche = v_precio_noche * 1.20;
                        ELSEIF v_tipo_habitacion = 'Triple' THEN
                            SET v_precio_noche = v_precio_noche * 1.30;
                        END IF;
                    END IF;

                    -- Calcular el precio total en base a las fechas y el precio por noche
                    SET v_total = v_precio_noche * DATEDIFF(v_fecha_fin, v_fecha_inicio);

                    -- Actualizar el precio total en la tabla de tickets
                    UPDATE tickets
                    SET precio_total = v_total
                    WHERE id_reservacion = p_reservacion_id;
                END;
                ";

                DB::unprepared($sql);
            }

};
