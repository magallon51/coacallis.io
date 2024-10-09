<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservacion;
use App\Models\AsignaHabitacion;
use App\Models\Hotel;

class LiberarReservaciones extends Command
{
    protected $signature = 'liberar:reservaciones';
    protected $description = 'Libera las habitaciones de reservaciones vencidas';

    public function handle()
    {
        $reservacionesVencidas = Reservacion::whereDate('fecha_fin', '<', now())->get();

        foreach ($reservacionesVencidas as $reservacion) {
            $asignacion = AsignaHabitacion::findOrFail($reservacion->id_asigna);
            
            // Incrementa la cantidad de habitaciones disponibles
            $asignacion->update([
                'cantidad_habitacion' => $asignacion->cantidad_habitacion + 1,
            ]);

            // Incrementa la cantidad de habitaciones disponibles en el hotel
            $hotel = Hotel::findOrFail($asignacion->id_hotel);
            $hotel->update([
                'habitaciones_disponibles' => $hotel->habitaciones_disponibles + 1,
            ]);

            // Otras operaciones o lógica adicional si es necesario
        }

        $this->info('Reservaciones liberadas correctamente.');
    }
}
