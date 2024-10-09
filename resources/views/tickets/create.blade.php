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
            background-color: #ffffff;
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
        <h1 class="text-center mb-4">Agregar Ticket</h1>

        <form method="POST" action="{{ route('tickets.store') }}">
            @csrf

            <style>
                /* Oculta el botón del calendario en navegadores compatibles */
                input[type="date"]::-webkit-calendar-picker-indicator {
                    display: none;
                }

                /* Oculta el botón del calendario en Firefox */
                input[type="date"] {
                    pointer-events: none;
                }
            </style>

            <div class="form-group">
                <label for="fecha_pago">Fecha de pago:</label>
                <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" required value="{{ old('scheduled_date', $now->format('Y-m-d')) }}">
            </div>


            <div class="form-group">
                <label for="id_reservacion">Selecciona una reservación:</label>
                <select class="form-control" name="id_reservacion" id="id_reservacion" required>
                    <option value="" disabled selected>Selecciona una reservación</option>
                    @foreach($reservaciones as $reservacion)
                        <option value="{{$reservacion->id_reservacion}}" data-precio-noche="{{ $reservacion->hotel->precio_noche }}">
                            {{$reservacion->nombre}} ({{$reservacion->fecha_inicio}} - {{$reservacion->fecha_fin}})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="precio_total">Precio Noche del Hotel:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                    </div>
                    <input type="number" class="form-control" id="precio_total" name="precio_total" required readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="hotel">Hotel:</label>
                <select class="form-control" name="id_hotel" id="hotel" required>
                    <option value="" disabled selected>Selecciona un hotel</option>
                    @foreach($reservaciones as $reservacion)
                        <option value="{{ $reservacion->hotel->id_hotel }}">{{ $reservacion->hotel->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="numero">Número Tarjeta:</label>
                <select class="form-control" name="id_tarjeta" id="numero" required>
                    <option value="" disabled selected>Selecciona una tarjeta</option>
                    @foreach($tarjetas as $tarjeta)
                        <option value="{{ $tarjeta->id_tarjeta }}">{{ $tarjeta->numero }}</option>
                    @endforeach
                </select>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>

        <!-- Script para calcular el precio total en tiempo real -->
        <script>
            document.getElementById('id_reservacion').addEventListener('change', function () {
                var selectedOption = this.options[this.selectedIndex];
                var precioNoche = parseFloat(selectedOption.getAttribute('data-precio-noche'));
                var precioTotal = precioNoche;

                document.getElementById('precio_total').value = precioTotal.toFixed(2);
            });
        </script>

    </div>
@endsection
