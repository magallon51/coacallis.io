<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Reservacion;
use App\Models\AsignaHabitacion;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReservacionController extends Controller
{

    public function __construct()
    {
        //$this->middleware('can:reservaciones.index')->only('index');
        $this->middleware('can:reservaciones.edit')->only('edit', 'update');
    }



    public function crearReserva(Request $request)
    {

        // Validar y obtener los datos del formulario
        $request->validate([
            'id_hotel' => 'required',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            // Agrega aquí las reglas de validación para los demás campos si es necesario
        ], [
            // Mensajes de error personalizados si es necesario
        ]);

        // Obtiene los datos validados del formulario
        $id_hotel = $request->input('id_hotel');
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');

        // Crear nueva instancia de Reservacion
        $reservacion = new Reservacion;

        // Asignar valores de la reservación
        // Ajusta esto según tu lógica para asignar valores a los campos de Reservacion
        $reservacion->id_hotel = $id_hotel;
        $reservacion->fecha_inicio = $fecha_inicio;
        $reservacion->fecha_fin = $fecha_fin;

        // Guardar la reservación
        $reservacion->save();

        // Actualizar la cantidad de habitaciones disponibles en el hotel
        $hotel = Hotel::findOrFail($id_hotel);
        //$tipo_habitacion = $reservacion->tipo_hab; // Ajusta esto según cómo almacenes el tipo de habitación en Reservacion

        // Obtener el tipo de habitación de la asignación relacionada con la reservación
        $tipo_habitacion = $reservacion->asignaHabitacion->habitacion->tipo_habitacion;

        // Actualizar la cantidad de habitaciones disponibles según el tipo seleccionado
        switch ($tipo_habitacion) {
            case 'Individual':
                $hotel->habitaciones_disponibles -= 1;
                break;
            case 'Doble':
                $hotel->habitaciones_disponibles -= 1;
                break;
            case 'Triple':
                $hotel->habitaciones_disponibles -= 1;
                break;
            // Agrega más casos según los tipos de habitación que tengas
        }

        // Guardar la actualización del hotel
        $hotel->save();

        // Puedes redirigir a una página de éxito o hacer cualquier otra acción necesaria
        return back()->with('success', 'Reservación creada exitosamente');

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        //$hoteles=Hotel::all();
        //$reservaciones=Reservacion::all();
        //return view("reservaciones.index",compact("reservaciones","hoteles"));
        // Obtén el usuario actualmente autenticado
        $user = Auth::user();

        // Obtener valores de la solicitud HTTP
        $hotel_id = $request->input('hotel_id');
        $hotel_nombre = $request->input('hotel_nombre');

        // Consulta de reservaciones
        if ($user->hasRole('Admin')) {
            // Si es un administrador, recupera todas las reservaciones
            $reservaciones = Reservacion::all();
        } else {
            // Si es un cliente, recupera solo sus reservaciones
            $reservaciones = Reservacion::where('user_id', $user->id)->get();
        }

        // Agregar información sobre si el ticket ya ha sido generado
        foreach ($reservaciones as $reservacion) {
            $reservacion->ticket_generado = Ticket::where('id_reservacion', $reservacion->id_reservacion)
                ->where('user_id', $user->id)
                ->exists();
        }

        // Pasa las reservaciones y otros datos a la vista
        return view("reservaciones.index", compact("reservaciones", "hotel_id", "hotel_nombre"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create($hotel_id, $hotel_nombre)
    {
        // Obtén la lista de asignaciones de habitaciones para el hotel específico
        $asignaciones = AsignaHabitacion::with('habitacion')
            ->where('id_hotel', $hotel_id)
            ->get();

        // Verificar si hay habitaciones disponibles
        $habitacionesDisponibles = $asignaciones->sum('cantidad_habitacion') > 0;

        // Obtén la información del hotel
        $hotel = Hotel::findOrFail($hotel_id);

        // Si no hay habitaciones disponibles, redirige o realiza la acción que consideres adecuada
        if (!$habitacionesDisponibles) {
            // Puedes redirigir a la página de índice de hoteles u otra página
            return redirect()->route('hoteles.index')->with('error', 'No hay habitaciones disponibles en este hotel.');
        }

        // Obtener el nombre del usuario autenticado
        $userName = Auth::user()->name;

        // Obtener ap y am del usuario autenticado (ajusta según tu modelo de usuario)
        $userAp = Auth::user()->ap;
        $userAm = Auth::user()->am;
        $userEmail = Auth::user()->email;

        return view('reservaciones.create', [
            'asignaciones' => $asignaciones,
            'hotel_id' => $hotel_id,
            'hotel_nombre' => $hotel_nombre,
            'habitacionesDisponibles' => $habitacionesDisponibles,
            'hotel' => $hotel,
            'userName' => $userName,
            'userAp' => $userAp,
            'userAm' => $userAm,
            'userEmail' => $userEmail,  // Agregar este valor
        ]);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // Validaciones
        $request->validate([
            'nombre' => 'required',
            'ap' => 'required',
            'am' => 'required',
            'correo' => 'required|email',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'id_hotel' => 'required|exists:hoteles,id_hotel',
            'id_asigna' => 'required|exists:asigna_habitacion,id_asigna',
            'cant_a' => 'required|integer',
            'cant_n' => 'required|integer',
        ]);

        // Obtener los datos validados del formulario
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');
        $id_asigna = $request->input('id_asigna');

        // Llamada al procedimiento almacenado
        $result = DB::selectOne('CALL check_reservation_procedure(?, ?, ?, @p_permitir_reservacion)', [
            $fecha_inicio,
            $fecha_fin,
            $id_asigna,
        ]);

        // Obtener el resultado de la variable de sesión
        $permitirReservacion = DB::selectOne('SELECT @p_permitir_reservacion as permitir_reservacion')->permitir_reservacion;

        // Verificar el resultado
        if ($permitirReservacion == 1) {
            // Permitir la reservación
            $user = auth()->user();
            // Crear nueva instancia de Reservacion
            $reservacion = new Reservacion;

            // Asignar valores de la reservación
            $reservacion->nombre = $request->nombre;
            $reservacion->ap = $request->ap;
            $reservacion->am = $request->am;
            $reservacion->correo = $request->correo;
            $reservacion->fecha_inicio = $request->fecha_inicio;
            $reservacion->fecha_fin = $request->fecha_fin;
            $reservacion->id_hotel = $request->id_hotel;
            $reservacion->id_asigna = $request->id_asigna;
            $reservacion->cant_a = $request->cant_a;
            $reservacion->cant_n = $request->cant_n;
            $reservacion->user_id = $user->id;

            // Guardar la reservación
            $reservacion->save();

            if ($user->hasRole('Admin')) {
                return redirect()->route('reservaciones.index')->with('success', 'Reservación exitosa'); // Redirige al índice de reservaciones
            } else {
                return redirect()->route('reservaciones.index')->with('success', 'Reservación exitosa');
            }
        } else {
            // Mostrar una alerta o manejar el caso en que la reservación no sea válida
            return back()->with('error', 'No es posible completar la reservación. Ya existe una reservación para la habitación seleccionada en las fechas especificadas.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservacion  $reservacion
     * @return \Illuminate\Http\Response
     */

    public function show(Reservacion $reservacion)
    {
        $hoteles=Hotel::all();
        return view("reservaciones.edit",compact("reservacion","hoteles"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reservacion  $reservacion
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        //$hoteles=Hotel::all();
        //return view("reservaciones.edit",compact("reservacion","hoteles"));

        $reservacion = Reservacion::findOrFail($id);
        $hoteles = Hotel::all();

        // Obtén la lista de asignaciones de habitaciones para el hotel específico
        $asignaciones = AsignaHabitacion::with('habitacion')
            ->where('id_hotel', $reservacion->id_hotel)
            ->get();

        // Verificar si hay habitaciones disponibles
        $habitacionesDisponibles = $asignaciones->sum('cantidad_habitacion') > 0;

        return view('reservaciones.edit', [
            'reservacion' => $reservacion,
            'hoteles' => $hoteles,
            'asignaciones' => $asignaciones,
            'habitacionesDisponibles' => $habitacionesDisponibles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservacion  $reservacion
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {

        // Validaciones, si es necesario
        $request->validate([
            'nombre' => 'required',
            'ap' => 'required',
            'am' => 'required',
            'correo' => 'required|email',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'id_hotel' => 'required|exists:hoteles,id_hotel',
            'id_asigna' => 'required|exists:asigna_habitacion,id_asigna',
            'cant_a' => 'required|integer',
            'cant_n' => 'required|integer',
        ]);

        // Obtén la reservación existente
        $reservacion = Reservacion::findOrFail($id);

        // Actualiza los campos de la reservación
        $reservacion->update([
            'nombre' => $request->nombre,
            'ap' => $request->ap,
            'am' => $request->am,
            'correo' => $request->correo,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'id_hotel' => $request->id_hotel,
            'cant_a' => $request->cant_a,
            'cant_n' => $request->cant_n,
        ]);

        // Actualiza el tipo de habitación solo si es diferente
        if ($reservacion->id_asigna != $request->id_asigna) {
            $reservacion->id_asigna = $request->id_asigna;

            // Guarda la actualización
            $reservacion->save();
        }

        // Redirige a la vista index
        return redirect()->route("reservaciones.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservacion  $reservacion
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $reservacion = Reservacion::find($id);
        $reservacion->delete();
        $reservacion->forceDelete();
        return redirect()->back();
    }

}
