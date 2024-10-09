<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "tickets";
    protected $primaryKey = "id_ticket";
    protected $fillable = ['fecha_pago', 'id_reservacion', 'id_hotel', 'id_tarjeta', 'precio_total','pdf_path'];

    public function getreservaciones()
    {
        return$this->hasOne(Reservacion::class,"id_reservacion","id_reservacion");
    }

    public function gethoteles()
    {
        return$this->hasOne(Hotel::class,"id_hotel","id_hotel");
    }

    public function gettarjetas()
    {
        return $this->hasOne(Tarjeta::class, "id_tarjeta", "id_tarjeta");
    }


}
