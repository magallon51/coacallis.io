@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Hoteles de Socios</h1>
    
        <a href="{{ route('socios.create') }}" class="btn btn-primary">Agregar</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Socio</th>
                    <th>Hotel</th>
                    <th>Caracteristicas</th>
                    <th>Servicios</th>
                    <th>Estado</th>
                    <th>Municipio</th>
                </tr>
            </thead>
            <tbody>
                @foreach($socios as $socio)
                    <tr>
                        <td>{{ $loop->index+1 }}</td>
                        <td>{{ $socio->hotel }}</td>
                        <td>{{ $socio->caracteristica }}</td>
                        <td>{{ $socio->servicio }}</td>
                        <td>{{ $socio->estado }}</td>
                        <td>{{ $socio->municipio }}</td>
                        <td>
                            
                            @can('socios.edit')
                            <a href="{{ route('socios.edit', $socio) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            @endcan

                            @can('socios.destroy')
                            <form action="{{ route('socios.destroy', $socio) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Borrar
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
