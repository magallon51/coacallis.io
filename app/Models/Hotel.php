<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Hotel extends Model
{
    use HasFactory;
    use SoftDeletes; 

    protected $table = "hoteles";
    protected $primaryKey = "id_hotel";
    protected $fillable = ["nombre", "caracteristica", "servicio", "precio_noche", "telefono", "id_ubicacion", "imagen"];

    public function create()
    {
        // Recupera la colección de hoteles con la relación tiposHabitacion
        $hoteles = Hotel::with('tiposHabitacion')->get();

        // Pasa $hoteles a la vista
        return view('reservaciones.create', compact('hoteles'));
    }

    public function getubicaciones()
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion', 'id_ubicacion');
    }

    public function habitaciones()
{
    return $this->belongsToMany(Habitacion::class, 'asigna_habitacion', 'id_hotel', 'id_habitacion')
        ->withPivot('cantidad_habitacion') // Esto carga la columna cantidad_habitacion en la relación
        ->withTimestamps();
}


}
