<?php

namespace App\Http\Controllers;
use App\Models\Ubicacion;
use App\Models\Hotel;
use App\Models\AsignaHabitacion;
use App\Models\Habitacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HotelController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:hoteles.edit')->only('edit', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $filtroEstado = $request->get('filtro_estado');
    $filtroMunicipio = $request->get('filtro_municipio');


    $query = $request->get('query');

    $hoteles = Hotel::when($filtroEstado, function ($query, $filtroEstado) 
    {
            return $query->whereHas('getubicaciones', function ($q) use ($filtroEstado) 
            {
                $q->where('estado', $filtroEstado);
            });
        })
        ->when($filtroMunicipio, function ($query, $filtroMunicipio) 
        {
            return $query->whereHas('getubicaciones', function ($q) use ($filtroMunicipio) 
            {
                $q->where('municipio', $filtroMunicipio);
            });
        })

        ->get();
        /*$hoteles = $hoteles->map(function ($hotel) {
            $hotel->habitaciones_disponibles = max(0, $hotel->habitaciones_disponibles);
            return $hotel;
        });*/

    // Obtén la información sobre tipos de habitaciones y su cantidad disponible
    $tiposHabitaciones = Hotel::with('habitaciones')
    ->join('asigna_habitacion', 'asigna_habitacion.id_hotel', '=', 'hoteles.id_hotel')
    ->join('habitaciones', 'asigna_habitacion.id_habitacion', '=', 'habitaciones.id_habitacion')
    ->select('hoteles.id_hotel', 'hoteles.nombre as nombre_hotel', 'habitaciones.tipo_habitacion', DB::raw('SUM(asigna_habitacion.cantidad_habitacion) as cantidad_disponible'))
    ->groupBy('hoteles.id_hotel', 'hoteles.nombre', 'habitaciones.tipo_habitacion')
    ->get();




    $ubicaciones = Ubicacion::all();

    return view("hoteles.index", compact("hoteles", "ubicaciones", "query", "filtroEstado", "filtroMunicipio"));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ubicaciones = Ubicacion::all();
        $habitaciones = Habitacion::all();

        return view("hoteles.create", compact("ubicaciones", "habitaciones"));
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
            "nombre" => "required",
            "caracteristica" => "required",
            "servicio" => "required",
            "precio_noche" => "required",
            "telefono" => "required|unique:hoteles",
            "id_ubicacion" => "required",
            "imagen" => "required",
            'habitaciones.*.cantidad' => 'sometimes|numeric|min:0',
        ]);

        $user = auth()->user();

        $hotel = new Hotel;
        $hotel->nombre = $request->nombre;
        $hotel->caracteristica = $request->caracteristica;
        $hotel->servicio = $request->servicio;
        $hotel->precio_noche = $request->precio_noche;
        $hotel->telefono = $request->telefono;
        $hotel->id_ubicacion = $request->id_ubicacion;
        $hotel->imagen = $request->imagen;
        $hotel->user_id = $user->id;
        $hotel->save();

        $asignaciones = collect($request->habitaciones)->filter(function ($habitacion) {
            return isset($habitacion['cantidad']) && $habitacion['cantidad'] > 0;
        })->mapWithKeys(function ($habitacion) {
            return [
                $habitacion['id_habitacion'] => ['cantidad_habitacion' => $habitacion['cantidad']],
            ];
        })->all();

        $hotel->habitaciones()->sync($asignaciones);

        return redirect()->route("hoteles.index");
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function show(Hotel $hotel)
    {
        $ubicaciones=Ubicacion::all();
        return view("hoteles.edit", compact("hotel","ubicaciones"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $hotel = Hotel::findOrFail($id);
        $ubicaciones = Ubicacion::all();
        $habitaciones = Habitacion::all(); // Agregado

        return view("hoteles.edit", compact("hotel", "ubicaciones", "habitaciones")); // Modificado
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "nombre" => "required",
            "caracteristica" => "required",
            "servicio" => "required",
            "precio_noche" => "required",
            "telefono" => "required",
            "id_ubicacion" => "required",
            "imagen" => "required", // Validación de la imagen
            'habitaciones.*.cantidad' => 'sometimes|numeric|min:0',
        ]);
    
        $hotel = Hotel::findOrFail($id);
    
        //if ($request->hasFile('imagen')) {
            // Subir y actualizar la imagen si se proporciona
            //$imageName = time() . '.' . $request->imagen->extension();
           // $request->imagen->move(public_path('img'), $imageName);
           // $hotel->update(["imagen" => $imageName]);
        //}
    
        // Actualizar otros campos del hotel
    $hotel->update([
        "nombre" => $request->nombre,
        "caracteristica" => $request->caracteristica,
        "servicio" => $request->servicio,
        "precio_noche" => $request->precio_noche,
        "telefono" => $request->telefono,
        "id_ubicacion" => $request->id_ubicacion,
        "imagen" => $request->imagen,
    ]);

    // Sincronizar las cantidades de habitaciones
    $asignaciones = collect($request->habitaciones)->filter(function ($habitacion) {
        return isset($habitacion['cantidad']) && $habitacion['cantidad'] > 0;
    })->mapWithKeys(function ($habitacion) {
        return [
            $habitacion['id_habitacion'] => ['cantidad_habitacion' => $habitacion['cantidad']],
        ];
    })->all();

    $hotel->habitaciones()->sync($asignaciones);

    return redirect()->route("hoteles.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hotel $hotel, $id_hotel)
    {

        $hotel = Hotel::find($id_hotel);


        if ($hotel) 
        {
            $hotel->delete();
            //$hotel->forceDelete();
            return redirect()->route('hoteles.index')->with('success', 'Hotel eliminado exitosamente');
        } 
        else 
        {
            return redirect()->route('hoteles.index')->with('error', 'El hotel no se encontró o ya ha sido eliminado');
        }

    }

    public function restore(Hotel $hotel, $id_hotel)
    {
        Hotel::withTrashed()->where('id', $id_hotel)->restore();

        return redirect()->route('hoteles.index')
            ->with('success', 'Hotel restaurado exitosamente');
    }
}
