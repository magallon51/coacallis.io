<?php

namespace App\Http\Controllers;

use App\Models\Tarjeta;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TarjetaUsuarioController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:tarjetas.index')->only('index');
        $this->middleware('can:tarjetas.edit')->only('edit', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tarjetas = Tarjeta::all();
        return view('tarjetas.index', compact('tarjetas')); // Corregido el retorno de la vista y pasando las tarjetas
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$tarjetas=Tarjeta::all();
        return view("tarjetas.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los campos del formulario
        $request->validate([
            "nombre" => "required|string|max:255",
            "ap" => "required|string|max:255",
            "am" => "required|string|max:255",
            "numero" => ["required", "string", "regex:/^\d{16,19}$/"],
            "fecha" => ["required", "string", "regex:/^(0[1-9]|1[0-2])\/?([0-9]{2})$/"],
            "cvc" => ["required", "string", "regex:/^\d{3}$/"],
        ]);

        // Verificar la fecha de vencimiento
        $fechaVencimiento = $request->input('fecha');
        $mes = intval(substr($fechaVencimiento, 0, 2));
        $anio = intval('20' . substr($fechaVencimiento, 3, 2));

        $fechaActual = new \DateTime();
        $fechaVencimiento = \DateTime::createFromFormat('m/Y', sprintf('%02d/%d', $mes, $anio));
        $fechaVencimiento->modify('last day of this month'); // Obtener el último día del mes de vencimiento

        if ($fechaVencimiento < $fechaActual) {
            return redirect()->back()->withErrors(['fecha' => 'La fecha de vencimiento de la tarjeta ya ha pasado.']);
        }

        // Obtener el usuario autenticado
        $user = auth()->user();

        // Crear nueva instancia de Tarjeta
        $tarjeta = new Tarjeta;

        // Asignar valores a la tarjeta
        $tarjeta->nombre = $request->nombre;
        $tarjeta->ap = $request->ap;
        $tarjeta->am = $request->am;
        $tarjeta->numero = $request->numero;
        $tarjeta->fecha = $request->fecha;
        $tarjeta->cvc = $request->cvc;
        $tarjeta->user_id = $user->id; // Asignar el ID del usuario autenticado

        // Guardar la tarjeta
        $tarjeta->save();

        // Redirigir según el rol del usuario
        if (auth()->user()->hasRole('Admin')) {
            return redirect()->route('tarjetas.index')->with('success', 'Tarjeta agregada exitosamente.');
        } else {
            return redirect()->route('tickets.create')->with('success', 'Tarjeta agregada exitosamente.');
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tarjeta  $tarjeta
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tarjeta = Tarjeta::find($id);
        return view('tarjetas.show', compact('tarjeta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tarjeta  $tarjeta
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $tarjeta = Tarjeta::findOrFail($id);
        return view('tarjetas.edit', compact('tarjeta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarjeta  $tarjeta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tarjeta = Tarjeta::findOrFail($id);



        $tarjeta->update([
            "nombre" => $request->nombre,
            "ap" => $request->ap,
            "am" => $request->am,
            "numero" => $request->numero,
            "fecha" => $request->fecha,
            "cvc" => $request->cvc,
        ]);

        return redirect()->route("tarjetas.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tarjeta  $tarjeta
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tarjeta = Tarjeta::find($id);

        $tarjeta->delete();
        $tarjeta->forceDelete();
        return redirect()->route("tarjetas.index");
    }
}
