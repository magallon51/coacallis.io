<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Hotel;


class Reservacion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "reservaciones";
    protected $primaryKey = "id_reservacion";
    protected $fillable = ['nombre', 'ap', 'am', 'correo', 'cant_a', 'cant_n', 'fecha_inicio', 'fecha_fin', 'id_hotel', 'id_asigna'];


    public function gethoteles()
    {
        return $this->hasOne(Hotel::class, "id_hotel", "id_hotel");
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, "id_hotel", "id_hotel");
    }

    public function asignaHabitacion()
    {
        return $this->belongsTo(AsignaHabitacion::class, 'id_asigna');
    }

    public function habitacion()
    {
        return $this->hasOneThrough(Habitacion::class, AsignaHabitacion::class, 'id_asigna', 'id_habitacion');
    }

}
