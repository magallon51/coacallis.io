<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SocioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $socios = Socio::all();
        return view('socios.index', compact('socios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view("socios.create");
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
            "hotel" => "required", 
            "caracteristica" => "required|string|max:255", 
            "servicio" => "required",
            "precio_noche" => "required|string|max:255",
            "telefono" => "required|string|max:10",
            "estado" => "required|string|max:255",
            "municipio" => "required|string|max:255",
        ], [
            // Mensajes personalizados de validación...
        ]);
    
        Socio::create([
            "hotel" => $request->hotel, 
            "caracteristica" => $request->caracteristica, 
            "servicio" => $request->servicio,
            "precio_noche" => $request->precio_noche,
            "telefono" => $request->telefono,
            "estado" => $request->estado,
            "municipio" => $request->municipio,
        ]);
    
        return redirect()->route("tarjetas.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Socio  $socio
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $socio = Socio::find($id);
        return view('socios.show', compact('socio'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Socio  $socio
     * @return \Illuminate\Http\Response
     */
    public function edit(Socio $socio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Socio  $socio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Socio $socio)
    {
        $socio = Socio::findOrFail($id);

        $socio->update([
            "hotel" => $request->hotel, 
            "caracteristica" => $request->caracteristica, 
            "servicio" => $request->servicio,
            "precio_noche" => $request->precio_noche,
            "telefono" => $request->telefono,
            "estado" => $request->estado,
            "municipio" => $request->municipio,
        ]);
    
        return redirect()->route("tarjetas.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Socio  $socio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Socio $socio)
    {
        $socio = Socio::find($id);

        $socio->delete();
        return redirect()->route("socios.index");
    }
}
