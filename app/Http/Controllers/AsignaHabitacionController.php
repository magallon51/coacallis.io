<?php

namespace App\Http\Controllers;
use App\Models\Habitacion;
use App\Models\Hotel;
use App\Models\AsignaHabitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignaHabitacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $asignaciones = AsignaHabitacion::with(['hotel', 'habitacion'])->get();

        return view('asignahabitaciones.index', compact('asignaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hoteles = Hotel::all();
        $habitaciones = Habitacion::all();

        return view('asignahabitaciones.create', compact('hoteles', 'habitaciones'));
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
            'id_hotel' => 'required',
            'id_habitacion' => 'required',
            'cantidad_habitacion' => 'required|integer',
            // Agrega más validaciones según sea necesario
        ]);

        // Verifica si la habitación ya está asignada al hotel
        $asignacionExistente = AsignaHabitacion::where('id_hotel', $request->id_hotel)
            ->where('id_habitacion', $request->id_habitacion)
            ->exists();

        if ($asignacionExistente) {
            return redirect()->route('asignahabitaciones.create')
                ->with('error', 'La habitación ya está asignada a este hotel.');
        }

        // Si no está asignada, realiza la asignación
        try {
            DB::beginTransaction();

            AsignaHabitacion::create([
                'id_hotel' => $request->id_hotel,
                'id_habitacion' => $request->id_habitacion,
                'cantidad_habitacion' => $request->cantidad_habitacion,
            ]);

            DB::commit();

            return redirect()->route('asignahabitaciones.create')
                ->with('success', 'Habitación asignada correctamente.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('asignahabitaciones.create')
                ->with('error', 'Error al asignar la habitación.');
        }

        return redirect()->route('asignahabitaciones.index')->with('success', 'Asigna Habitacion creada exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $asignacion = AsignaHabitacion::findOrFail($id);
        return view('asignahabitaciones.show', compact('asignacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $asignacion = AsignaHabitacion::findOrFail($id);
        $hoteles = Hotel::all();
        $habitaciones = Habitacion::all();
        return view('asignahabitaciones.edit', compact('asignacion', 'hoteles', 'habitaciones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $asignacion = AsignaHabitacion::findOrFail($id);

        $request->validate([
            'id_hotel' => 'required',
            'id_habitacion' => 'required',
            'cantidad_habitacion' => 'required|integer',
            // Agrega más validaciones según sea necesario
        ]);

        // Puedes agregar la misma lógica de verificación de asignación existente aquí antes de actualizar

        $asignacion->update([
            'id_hotel' => $request->id_hotel,
            'id_habitacion' => $request->id_habitacion,
            'cantidad_habitacion' => $request->cantidad_habitacion,
        ]);

        return redirect()->route('asignahabitaciones.index')
            ->with('success', 'Asignación de habitación actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asignacion = AsignaHabitacion::findOrFail($id);
        $asignacion->delete();

        return redirect()->route('asignahabitaciones.index')
            ->with('success', 'Asignación de habitación eliminada correctamente.');
    }
}
