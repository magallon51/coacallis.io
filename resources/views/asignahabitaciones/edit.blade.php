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

        .card {
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #fff
            color: #fff;
            padding: 10px;
        }

        .card-body,
        .card-footer {
            padding: 15px;
        }
    </style>

    <script>
        function hideAndStyleAlerts() {
            var alerts = document.querySelectorAll('.alert');

            alerts.forEach(function(alert) {
                alert.classList.add('custom-alert');

                setTimeout(function() {
                    alert.style.display = 'none';
                }, 5000);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            hideAndStyleAlerts();
        });
    </script>
    <div class="container">
        <h1>Editar Asignación de Habitación</h1>

        <form action="{{ route('asignahabitaciones.update', $asignacion->id_asigna) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="id_hotel">Seleccionar Hotel:</label>
                <select name="id_hotel" id="id_hotel" class="form-control">
                    @foreach($hoteles as $hotel)
                        <option value="{{ $hotel->id_hotel }}" @if($hotel->id_hotel == $asignacion->id_hotel) selected @endif>{{ $hotel->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="id_habitacion">Seleccionar Tipo de Habitación:</label>
                <select name="id_habitacion" id="id_habitacion" class="form-control">
                    @foreach($habitaciones as $habitacion)
                        <option value="{{ $habitacion->id_habitacion }}" @if($habitacion->id_habitacion == $asignacion->id_habitacion) selected @endif>{{ $habitacion->tipo_habitacion }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="cantidad_habitacion">Cantidad Disponible:</label>
                <input type="number" class="form-control" id="cantidad_habitacion" name="cantidad_habitacion" value="{{ $habitacion->cantidad_habitacion }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Asignación</button>
            <a href="{{ route('asignahabitaciones.index') }}" class="btn btn-secondary">Volver</a>
        </form>
    </div>
@endsection
