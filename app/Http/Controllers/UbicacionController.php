<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class UbicacionController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:ubicaciones.index')->only('index');
        $this->middleware('can:ubicaciones.edit')->only('edit', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ubicaciones = Ubicacion::all();
        return view('ubicaciones.index', compact('ubicaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ubicaciones = Ubicacion::all();
        $estados = [
            'Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 'Chihuahua',
            'Coahuila', 'Colima', 'Ciudad de México', 'Durango', 'Guanajuato', 'Guerrero', 'Hidalgo', 'Jalisco',
            'Estado de México', 'Michoacán', 'Morelos', 'Nayarit', 'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro',
            'Quintana Roo', 'San Luis Potosí', 'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz',
            'Yucatán', 'Zacatecas',
        ];
        return view('ubicaciones.create', compact('estados'));
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
            'estado' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
        ]);

        try {
            $existingUbicacion = Ubicacion::where('estado', $request->estado)
                ->where('municipio', $request->municipio)
                ->first();

            if ($existingUbicacion) {
                return redirect()->route('ubicaciones.create')->with('error', 'No se pueden agregar ubicaciones duplicadas');
            }

            Ubicacion::create([
                "estado" => $request->estado,
                "municipio" => $request->municipio,
            ]);

            return redirect()->route("ubicaciones.index")->with('success', 'Ubicación agregada correctamente');
        } catch (QueryException $e) {
            return redirect()->route('ubicaciones.create')->with('error', 'Error al agregar la ubicación');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ubicacion = Ubicacion::find($id);

        if (!$ubicacion) {
            return abort(404);
        }

        return view('ubicaciones.show', compact('ubicacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ubicacion = Ubicacion::findOrFail($id);
        return view('ubicaciones.edit', compact('ubicacion'));
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
        $ubicacion = Ubicacion::findOrFail($id);
        $ubicacion->update([
            "estado" => $request->estado,
            "municipio" => $request->municipio,
        ]);

        return redirect()->route("ubicaciones.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ubicacion $ubicacion, $id)
    {
        $ubicacion = Ubicacion::find($id);

        if ($ubicacion) 
        {
            $ubicacion->delete();
            //$ubicacion->forceDelete();
            return redirect()->route('ubicaciones.index')->with('success', 'Ubicación eliminada exitosamente');
        } 
        else 
        {
            return redirect()->route('ubicaciones.index')->with('error', 'La ubicación no se encontró o ya ha sido eliminada');
        }
        
    }
}
