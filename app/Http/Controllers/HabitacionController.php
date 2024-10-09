<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habitacion; 

class HabitacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $habitaciones = Habitacion::all();
        return view('habitaciones.index', compact('habitaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('habitaciones.create');
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
            'tipo_habitacion' => 'required|string',
            //'cantidad_habitacion' => 'required|integer',
            // Puedes agregar más validaciones según sea necesario
        ]);
    
        Habitacion::create([
            'tipo_habitacion' => $request->tipo_habitacion,
            //'cantidad_habitacion' => $request->cantidad_habitacion,
            // Puedes asignar más campos aquí según sea necesario
        ]);
        
        return redirect()->route('habitaciones.index')->with('success', 'Habitación creada exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $habitacion = Habitacion::findOrFail($id);
        return view('habitaciones.show', compact('habitacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $habitacion = Habitacion::findOrFail($id);
        return view('habitaciones.edit', compact('habitacion'));
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
        $request->validate([
            'tipo_habitacion' => 'required|string',
            //'cantidad_habitacion' => 'required|integer',
            // Puedes agregar más validaciones según sea necesario
        ]);

        $habitacion = Habitacion::findOrFail($id);

        $habitacion->update([
            'tipo_habitacion' => $request->tipo_habitacion,
            //'cantidad_habitacion' => $request->cantidad_habitacion,
            // Puedes actualizar más campos aquí según sea necesario
        ]);

        return redirect()->route('habitaciones.index')->with('success', 'Habitación actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $habitacion = Habitacion::findOrFail($id);
        $habitacion->delete();

        return redirect()->route('habitaciones.index')->with('success', 'Habitación eliminada exitosamente.');
    }
}
