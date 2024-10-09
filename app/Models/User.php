<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Reservacion;
use App\Models\Tarjeta;
use App\Models\Hotel;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use SoftDeletes;

     

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'ap',
        'am',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class);
    }

    public function tarjetas()
    {
        return $this->hasMany(Tarjeta::class);
    }

    public function hoteles()
    {
        return $this->hasMany(Hotel::class);
    }
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id'); // Asegúrate de que 'user_id' sea la clave foránea correcta
    }

}

