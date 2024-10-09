<?php

namespace App\Http\Controllers;

use App\Models\Reservacion;
use App\Models\Tarjeta;
use App\Models\Hotel;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;

use Illuminate\Http\Request;

class TicketController extends Controller
{

    public function calcularTotal($p_reservacion_id)
    {
        // Realizar la llamada al procedimiento almacenado y obtener el precio total
        $result = DB::select("CALL calcularTotal($p_reservacion_id)");
        $precioTotal = $result[0]->precio_total;

        // Devolver el precio total en formato JSON
        return response()->json(['precio_total' => $precioTotal]);
    }

    public function __construct()
    {
        $this->middleware('can:tickets.edit')->only('edit', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            // Si es un administrador, obtener todos los tickets
            $tickets = Ticket::all();
        } else {
            // Si es un cliente, obtener solo sus tickets a través de la relación en el modelo User
            $tickets = $user->tickets;
        }

        $reservaciones = Reservacion::all();
        $hoteles = Hotel::all();
        $tarjetas = Tarjeta::all();

        return view("tickets.index", compact("tickets", "reservaciones", "hoteles", "tarjetas"));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function create(Request $request)
    {
        $user = auth()->user();

        $reservaciones = $user->hasRole('Admin','Cliente')
            ? Reservacion::all()
            : $user->reservaciones;

        $tarjetas = $user->hasRole('Admin','Cliente')
            ? Tarjeta::all()
            : $user->tarjetas;

        $hoteles = $user->hasRole('Admin','Cliente')
            ? Hotel::all()
            : $user->hoteles;

        $now = Carbon::now();

        // Obtener el precio total utilizando el procedimiento almacenado
        $p_reservacion_id = $request->input('id_reservacion'); // Ajusta esto según la lógica de tu aplicación

        // Utiliza un marcador de posición en el llamado al procedimiento almacenado
        DB::select("CALL calcularTotal(?)", [$p_reservacion_id]);

        // Ahora, obtén la información de la reservación para mostrarla en la vista
        $reservacion = Reservacion::find($p_reservacion_id);

        return view('tickets.create', compact('reservaciones', 'hoteles', 'tarjetas', 'now', 'reservacion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $request->validate([
            'fecha_pago' => 'required',
            'precio_total' => 'required',
            "id_reservacion" => "required",
            "id_hotel" => "required",
            "id_tarjeta" => "required",
        ], [
            "id_reservacion.required" => "El ID de Reservación es requerido.",
            "id_hotel.required" => "El ID de Hotel es requerido.",
            "id_tarjeta.required" => "El ID de Tarjeta es requerido.",
        ]);

        $p_reservacion_id = $request->input('id_reservacion');

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Crear el ticket con un valor inicial para precio_total
        $ticket = new Ticket([
            'fecha_pago' => $request->fecha_pago,
            'precio_total' => $request->precio_total,
            'id_reservacion' => $request->id_reservacion,
            'id_hotel' => $request->id_hotel,
            'id_tarjeta' => $request->id_tarjeta,
        ]);

        // Asociar el ticket al usuario
        $user->tickets()->save($ticket);

        // Llamado al procedimiento almacenado para calcular el total
        DB::select("CALL calcularTotal(?)", [$p_reservacion_id]);

        return redirect()->route('tickets.index')->with('success', 'Ticket creado exitosamente');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        $reservaciones=Reservacion::all();
        $hoteles=Hotel::all();
        $tarjetas=Tarjeta::all();
        return view("tickets.edit",compact("ticket","reservaciones","hoteles","tarjetas"));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        $tickets=Ticket::all();
        $reservaciones=Reservacion::all();
        $hoteles=Hotel::all();
        $tarjetas=Tarjeta::all();
        return view("tickets.edit",compact("ticket","reservaciones", "hoteles", "tarjetas"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $ticket->update([
            "fecha_pago"=>$request->fecha_pago,
            "id_reservacion"=>$request->id_reservacion,
            "id_hotel"=>$request->id_hotel,
            "id_tarjeta"=>$request->id_tarjeta,
            "precio_total"=>$request->precio_total,
        ]);

        return redirect()->route("tickets.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        $ticket->forceDelete();
        return redirect()->back();

    }

    public function generatePDF($id)
    {
        // Obtener el ticket junto con sus relaciones
        $ticket = Ticket::with(['getreservaciones', 'gethoteles', 'gettarjetas'])->findOrFail($id);

        // Datos para la vista del PDF
        $data = ['tickets' => [$ticket]];

        // Generar el PDF
        $pdf = PDF::loadView('tickets.pdf', $data)->setPaper([0, 0, 410, 500], 'portrait');

        // Nombre del archivo PDF
        $fileName = 'ticket_' . $ticket->id_ticket . '_' . time() . '.pdf';

        // Guardar el archivo PDF en el disco público
        Storage::disk('public')->put($fileName, $pdf->output());

        // Actualizar la ruta del PDF en el modelo del ticket
        $ticket->pdf_path = $fileName;
        $ticket->save();

        // Devolver el archivo PDF al usuario
        return response()->file(storage_path('app/public/' . $fileName));
    }
}
