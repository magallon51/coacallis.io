<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Habitacion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "habitaciones";
    protected $primaryKey = 'id_habitacion';
    protected $fillable = ['tipo_habitacion'];
}
