@extends("layouts.app")

@section("content")
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
        <h1>Editar Tickets</h1>
        <form method="POST" action="{{ route('tickets.update', $ticket->id_ticket) }}">
            @csrf
            @method("PUT")

            <div class="form-group">
                <label for="fecha_pago">Fecha Pago:</label>
                <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" value="{{ $ticket->fecha_pago }}">
            </div>

            <div class="form-group">
                <label for="id_reservacion">Selecciona una reservación:</label>
                <select class="form-control" name="id_reservacion" id="id_reservacion">
                    @foreach ($reservaciones as $reservacion)
                        <option value="{{ $reservacion->id_reservacion }}" {{ $reservacion->id_reservacion == $ticket->id_reservacion ? 'selected' : '' }}>
                            {{ $reservacion->nombre }} {{ $reservacion->fecha_inicio }} {{ $reservacion->fecha_fin }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="precio_total">Precio Total:</label>
                <input type="number" class="form-control" id="precio_total" name="precio_total" value="{{ $ticket->precio_total }}">
            </div>

            <div class="form-group">
                <label for="id_persona">Selecciona una persona:</label>
                <select class="form-control" name="id_persona" id="id_persona">
                    @foreach ($reservaciones as $reservacion)
                        <option value="{{ $reservacion->id_persona }}" {{ $reservacion->id_persona == $reservacion->id_persona ? 'selected' : '' }}>
                            {{ $reservacion->nombre }} {{ $reservacion->ap }} {{ $reservacion->am }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="hotel">Hotel al que pertenece:</label>
                <select class="form-control" name="id_hotel" id="id_hotel">
                    <option value="" disabled selected>Selecciona un hotel</option>
                    @foreach($hoteles as $hotel)
                        <option value="{{ $hotel->id_hotel }}" {{ $hotel->id_hotel == $hotel->id_hotel ? 'selected' : '' }}>
                            {{ $hotel->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="tarjeta">Número de tarjeta:</label>
                <select class="form-control" name="id_tarjeta" id="id_tarjeta">
                    <option value="" disabled selected>Selecciona un hotel</option>
                    @foreach($tarjetas as $tarjeta)
                        <option value="{{ $tarjeta->id_tarjeta }}" {{ $tarjeta->id_tarjeta == $tarjeta->id_tarjeta ? 'selected' : '' }}>
                            {{ $tarjeta->numero }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
@endsection