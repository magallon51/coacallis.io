<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class AsignaHabitacion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "asigna_habitacion";
    protected $primaryKey = "id_asigna";
    protected $fillable = ["id_hotel", "id_habitacion", 'cantidad_habitacion'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel', 'id_hotel');
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'id_habitacion');
    }


}
