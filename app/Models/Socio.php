<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Socio extends Model
{
    use HasFactory;
    use SoftDeletes; 

    protected $table = "socios";
    protected $primaryKey = "id_socio";
    protected $fillable = ["hotel", "caracteristica", "servicio", "precio_noche", "telefono", "estado","municipio" ,"imagen"];
}
