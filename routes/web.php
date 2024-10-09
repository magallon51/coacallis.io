<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\TarjetaUsuarioController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\ReservacionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\AsignaHabitacionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
  //  return view('welcome');
//});
// Redirección de la ruta raíz a /login
Route::get('/', function () {
  return redirect('/login');
});

// Rutas de autenticación
Auth::routes();

// Rutas de usuario
Route::delete('/users/{user}', 'UserController@destroy')->name('users.destroy');
Route::resource('users', UserController::class);

// Rutas de Ubicaciones
Route::resource("ubicaciones", UbicacionController::class);

// Rutas de Hoteles
Route::resource("hoteles", HotelController::class);

//Habitaciones
Route::resource("habitaciones", HabitacionController::class);

//Asigna Habitaciones
Route::resource("asignahabitaciones", AsignaHabitacionController::class);

// Rutas de Tarjetas
Route::resource("tarjetas", TarjetaUsuarioController::class);

// Rutas de Personas
Route::resource("personas", PersonaController::class);

// Rutas de Socios
Route::resource("socios", SocioController::class);

// Rutas de Reservaciones
Route::get('/reservaciones/create/{hotel_id}/{hotel_nombre}', [ReservacionController::class, 'create'])->name('reservaciones.create');
Route::get('/reservaciones/{reservacion}/edit', [ReservacionController::class, 'edit'])->name('reservaciones.edit');
Route::put('/reservaciones/{reservacion}', [ReservacionController::class, 'update'])->name('reservaciones.update');
Route::resource('reservaciones', ReservacionController::class)->except(['create', 'edit']);


// Rutas de Tickets
Route::resource("tickets", TicketController::class);
Route::get('tickets/{id}/generate-pdf', [TicketController::class, 'generatePDF'])->name('tickets.generatePDF');

// Rutas de Permisos de Usuarios
Route::resource("users", UserController::class)->only(['index', 'edit', 'update']);

// Ruta de Inicio (Home)
Route::get('/home', [HotelController::class, 'index'])->name('home'); // Cambia HomeController::class por HotelController::class
