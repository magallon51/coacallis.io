@extends('layouts.app')

@section('content')
    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <style>
        .custom-alert {
            font-family: 'Times New Roman', serif;
            text-align: center;
            margin: 0 auto;
            font-size: 20px; /* Ajusta el tamaño de la fuente según tus preferencias */
        }
    </style>

    <script>
        // Función para ocultar y aplicar estilos a las alertas después de un tiempo determinado
        function hideAndStyleAlerts() {
            var alerts = document.querySelectorAll('.alert');

            alerts.forEach(function(alert) {
                alert.classList.add('custom-alert'); // Agregar clase de estilo personalizada

                setTimeout(function() {
                    alert.style.display = 'none';
                }, 5000); // Ocultar después de 5 segundos
            });
        }

        // Llamar a la función al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            hideAndStyleAlerts();
        });
    </script>

    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease; /* Agrega la transición a todos los elementos .card */
        }

        .card:hover {
            transform: scale(1.05); /* Ajusta el valor según tus preferencias */
        }

        .no-rooms-alert {
            margin-top: 10px;
            color: red;
        }

        @font-face {
            font-family: 'Aztec';
            src: url('font/Aztec.ttf');
        }

        .card-img-top {
            height: 300px;
            object-fit: cover;
        }
    </style>

    <div class="container">
        <h1 class="text-center mb-4 ">Hoteles Disponibles</h1>

        <!-- Formulario de búsqueda -->
        <form action="{{ route('hoteles.index') }}" method="GET">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="filtro_estado" class="form-label">Filtrar por Estado:</label>
                    <select class="form-select" id="filtro_estado" name="filtro_estado">
                        <option value="">Todos los Estados</option>
                        @foreach($ubicaciones as $ubicacion)
                            <option value="{{ $ubicacion->estado }}">{{ $ubicacion->estado }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="filtro_municipio" class="form-label">Filtrar por Municipio:</label>
                    <select class="form-select" id="filtro_municipio" name="filtro_municipio">
                        <option value="">Todos los Municipios</option>
                        @foreach($ubicaciones as $ubicacion)
                            <option value="{{ $ubicacion->municipio }}">{{ $ubicacion->municipio }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Aplicar Filtro</button>

                    @can('hoteles.create')
                        <a href="{{ route('hoteles.create') }}" class="btn btn-primary">Agregar Hotel</a>
                    @endcan
                </div>
            </div>
        </form>

        <!-- Botón para regresar a la lista general -->
        @if(request()->has('search'))
            <a href="{{ route('hoteles.index') }}" class="btn btn-secondary mb-3">Regresar a la lista general</a>
        @endif

        @if ($hoteles->isEmpty())
            <p>No se encontraron hoteles.</p>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                @foreach($hoteles as $hotel)
                    @php
                        // Define una variable para el color de borde basado en el precio
                        $borderColor = '';
                        $textColor = '';
                        if ($hotel->precio_noche >= 0 && $hotel->precio_noche <= 199) {
                            $borderColor = 'border-success'; // Borde verde
                            $textColor = 'text-success'; // Texto verde
                        } elseif ($hotel->precio_noche >= 200 && $hotel->precio_noche <= 499) {
                            $borderColor = 'border-info'; // Borde azul
                            $textColor = 'text-info'; // Texto azul
                        } elseif ($hotel->precio_noche >= 500 && $hotel->precio_noche <= 999) {
                            $borderColor = 'border-warning'; // Borde amarillo
                            $textColor = 'text-warning'; // Texto amarillo
                        } else {
                            $borderColor = 'border-danger'; // Borde rojo
                            $textColor = 'text-danger'; // Texto rojo
                        }
                    @endphp

                    <div class="col">
                        <div class="card h-100 {{ $borderColor }}">
                            <div class="card-header {{ $borderColor }} {{ $textColor }}">
                                <h5><b>{{ $hotel->nombre }}</b></h5>
                            </div>
                            <a href="{{ route('reservaciones.create', ['hotel_id' => $hotel->id_hotel, 'hotel_nombre' => $hotel->nombre]) }}">
                                <img src="{{ asset('img/' . $hotel->imagen) }}" alt="Imagen del hotel" class="card-img-top">
                            </a>

                            <div class="card-body">
                                <p class="card-text"><b>Precio por Noche:</b> $ {{ number_format($hotel->precio_noche, 2) }}</p>
                                <p class="card-text"><b>Ubicación:</b> {{ $hotel->getubicaciones->estado }}, {{ $hotel->getubicaciones->municipio }}</p>
                                <p class="card-text"><b>Tipos de Habitación Disponibles:</b><br>
                                    @php
                                        $habitacionesDisponibles = false;
                                        foreach($hotel->habitaciones as $habitacion)
                                        {
                                            if ($habitacion->pivot->cantidad_habitacion > 0) {
                                                $habitacionesDisponibles = true;
                                                break;
                                            }
                                        }
                                    @endphp

                                    @if ($habitacionesDisponibles)
                                        @foreach($hotel->habitaciones as $habitacion)
                                            {{ $habitacion->tipo_habitacion }} (Disponibles: {{ $habitacion->pivot->cantidad_habitacion }})<br>
                                @endforeach
                                @else
                                    <p class="no-rooms-alert">No hay habitaciones disponibles en este momento.</p>
                                    @endif
                                    </p>
                            </div>
                            <div class="card-footer text-center">
                                @can('hoteles.edit')
                                    <a href="{{ route('hoteles.edit', $hotel) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                @endcan

                                @can('hoteles.destroy')
                                    <form action="{{ route('hoteles.destroy', $hotel) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Borrar
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
