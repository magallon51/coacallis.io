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
    <h1 class="mb-4">Editar Reservación</h1>
    <form method="POST" action="{{ route('reservaciones.update', $reservacion->id_reservacion) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $reservacion->nombre }}" required>
        </div>

        <div class="form-group">
            <label for="ap">Apellido Paterno:</label>
            <input type="text" class="form-control" id="ap" name="ap" value="{{ $reservacion->ap }}" required>
        </div>

        <div class="form-group">
            <label for="am">Apellido Materno:</label>
            <input type="text" class="form-control" id="am" name="am" value="{{ $reservacion->am }}" required>
        </div>

        <div class="form-group">
            <label for="correo">Correo Electrónico:</label>
            <input type="text" class="form-control" id="correo" name="correo" value="{{ $reservacion->correo }}" required>
        </div>

        <div class="form-group">
            <label for="fecha_inicio">Fecha de Entrada:</label>
            <input type="date" class="form-control datepicker" id="fecha_inicio" name="fecha_inicio" value="{{ $reservacion->fecha_inicio }}" required>
        </div>

        <div class="form-group">
            <label for="fecha_fin">Fecha de Salida:</label>
            <input type="date" class="form-control datepicker" id="fecha_fin" name="fecha_fin" value="{{ $reservacion->fecha_fin }}" required>
        </div>

        <div class="form-group">
            <label for="id_asigna">Tipo de Habitación:</label>
            @if($habitacionesDisponibles)
                <select name="id_asigna" id="id_asigna" class="form-control" required>
                    @foreach($asignaciones as $asignacion)
                        @if($asignacion->cantidad_habitacion > 0)
                            <option value="{{ $asignacion->id_asigna }}" {{ $reservacion->id_asigna == $asignacion->id_asigna ? 'selected' : '' }}>
                                {{ $asignacion->habitacion->tipo_habitacion }}
                            </option>
                        @endif
                    @endforeach
                </select>
            @else
                <p>No hay habitaciones disponibles en este momento.</p>
            @endif
        </div>

        <div class="form-group">
            <label for="cant_a">Cantidad de Adultos:</label>
            <input type="number" name="cant_a" class="form-control" value="{{ $reservacion->cant_a }}" required>
        </div>

        <div class="form-group">
            <label for="cant_n">Cantidad de Niños:</label>
            <input type="number" name="cant_n" class="form-control" value="{{ $reservacion->cant_n }}" required>
        </div>

        <div class="form-group">
            <label for="id_hotel">Hotel Seleccionado:</label>
            <select class="form-control" name="id_hotel" id="id_hotel" required>
                <option value="{{ $reservacion->id_hotel }}" selected>{{ $reservacion->hotel->nombre }}</option>
            </select>

            @error('id_hotel')
                <span class="invalid-feedback" style="display: block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const datepickers = document.querySelectorAll('.datepicker');

                datepickers.forEach(function (datepicker) {
                    datepicker.addEventListener('input', function () {
                        const currentDate = new Date().toISOString().split('T')[0];
                        const selectedDate = datepicker.value;
                        const lockIconId = datepicker.getAttribute('id') === 'fecha_inicio' ? 'lock-icon-inicio' : 'lock-icon-fin';
                        const lockIcon = document.getElementById(lockIconId);

                        if (selectedDate < currentDate) {
                            datepicker.value = '';
                            datepicker.classList.add('past-date');
                            if (lockIcon) {
                                lockIcon.style.display = 'inline';
                            }
                        } else {
                            datepicker.classList.remove('past-date');
                            if (lockIcon) {
                                lockIcon.style.display = 'none';
                            }
                        }
                    });
                });
            });
        </script>

        <style>
            .past-date {
                border: 2px solid red;
            }

            .date-icon {
                display: none;
                color: red;
            }
        </style>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
    </form>

    <div class="text-center mt-3">
        <a href="{{ route('reservaciones.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>
@endsection
