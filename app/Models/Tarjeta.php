<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarjeta extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "tarjetas";
    protected $primaryKey = "id_tarjeta";
    protected $fillable = ["nombre", "ap", "am","numero","fecha", "cvc"];
}
