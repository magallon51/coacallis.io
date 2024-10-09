@extends('layouts.app')

@section('content')


    <div class="container">



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



        <!-- Agrega esto al final de tu vista -->
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

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title mb-4">Información del Hotel</h2>
                            <!-- Información del hotel -->
                            <p><strong>Características:</strong> {{ $hotel->caracteristica }}</p>
                            <p><strong>Servicios:</strong> {{ $hotel->servicio }}</p>
                            <p><strong>Precio por Noche:</strong> $ {{ number_format($hotel->precio_noche, 2) }}</p>
                            <p><strong>Ubicación:</strong> {{ $hotel->getubicaciones->estado }}, {{ $hotel->getubicaciones->municipio }}</p>
                            <p><strong>Tipos de Habitación Disponibles:</strong><br>
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
                                    <span class="no-rooms-alert">No hay habitaciones disponibles en este momento.</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">

                    <div class="card">
                        <div class="card-body">
                            <button type="button" class="btn btn-primary float-start me-3" data-bs-toggle="modal" data-bs-target="#infoModal">
                                Información
                            </button>

                            <!-- Modal de Información -->
                            <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="infoModalLabel">Información al reservar</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Dependiendo de la habitación seleccionada, el precio por noche puede aumentar.</p>
                                            <p>Además, la capacidad para hospedar más o menos personas también dependerá de la habitación elegida.</p>
                                            <p>El precio de la habitación "Individual" no aumenta el precio base.</p>
                                            <p>Habitación "Doble" su precio aumenta un 20%.</p>
                                            <p>Habitación "Triple" su precio aumenta un 30%.</p>
                                            <p>Se considerará como adulto a niños mayores de 12 años.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('reservaciones.store') }}">
                                @csrf

                                <h2 class="text-center mb-4">Agregar Reservación</h2>

                                <!-- Información personal y fechas de reserva -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nombre">Nombre(s):</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $userName) }}" required>
                                        </div>

                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="ap">Apellido Paterno:</label>
                                            <input type="text" class="form-control" id="ap" name="ap" value="{{ old('ap', $userAp) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="am">Apellido Materno:</label>
                                            <input type="text" class="form-control" id="am" name="am" value="{{ old('am', $userAm) }}" required>
                                        </div>
                                    </div>

                                    <p></p>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="email">Correo Electrónico:</label>
                                            <input type="email" class="form-control" id="email" name="correo" value="{{ old('correo', $userEmail) }}" required>
                                        </div>
                                    </div>


                                    <p></p>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fecha_inicio">Fecha de Entrada:</label>
                                                <input type="date" class="form-control datepicker" id="fecha_inicio" name="fecha_inicio" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fecha_fin">Fecha de Salida:</label>
                                                <input type="date" class="form-control datepicker" id="fecha_fin" name="fecha_fin" required disabled>
                                                <span id="fecha-fin-message" class="text-danger" style="display: none;">Selecciona primero la fecha de entrada</span>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const fechaInicioInput = document.getElementById('fecha_inicio');
                                            const fechaFinInput = document.getElementById('fecha_fin');
                                            const fechaFinMessage = document.getElementById('fecha-fin-message');

                                            fechaInicioInput.min = new Date().toISOString().split('T')[0];

                                            fechaInicioInput.addEventListener('input', function () {
                                                const currentDate = new Date().toISOString().split('T')[0];
                                                const selectedDate = fechaInicioInput.value;

                                                if (selectedDate < currentDate) {
                                                    fechaInicioInput.value = currentDate;
                                                }

                                                fechaFinInput.min = fechaInicioInput.value;
                                                fechaFinInput.disabled = false;
                                                fechaFinMessage.style.display = 'none';
                                            });

                                            fechaFinInput.addEventListener('input', function () {
                                                fechaInicioInput.max = fechaFinInput.value;
                                            });

                                            fechaFinInput.addEventListener('focus', function () {
                                                if (fechaInicioInput.value === '') {
                                                    fechaFinInput.blur();
                                                    fechaFinMessage.style.display = 'inline';
                                                } else {
                                                    fechaFinMessage.style.display = 'none';
                                                }
                                            });
                                        });
                                    </script>

                                    <div class="form-group">
                                        <label for="id_asigna">Tipo de Habitación:</label>
                                        @if($habitacionesDisponibles)
                                            <select name="id_asigna" id="id_asigna" class="form-control" required>
                                                @foreach($asignaciones as $asignacion)
                                                    @if($asignacion->cantidad_habitacion > 0)
                                                        <option value="{{ $asignacion->id_asigna }}">{{ $asignacion->habitacion->tipo_habitacion }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @else
                                            <span class="no-rooms-alert">No hay habitaciones disponibles en este momento.</span>
                                        @endif
                                    </div>
                                    <p></p>
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="cant_a">Cantidad de Adultos:</label>
                                                <select name="cant_a" class="form-control" required>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="cant_n">Cantidad de Niños:</label>
                                                <select name="cant_n" class="form-control" required>
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                </select>
                                            </div>
                                        </div>
                                        <small class="text-muted">Solo se contara como niño a personas menores de 12 años.</small>
                                    </div>



                                    <div class="form-group">
                                        <label for="id_hotel">Hotel Seleccionado:</label>
                                        <select class="form-control" name="id_hotel" id="id_hotel" required>
                                            <option value="{{ $hotel_id }}" selected>{{ $hotel_nombre }}</option>
                                        </select>

                                        @error('id_hotel')
                                        <span class="invalid-feedback" style="display: block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                        @enderror
                                    </div>

                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-primary" id="reservarBtn">Reservar</button>
                                        @can('reservaciones.index')
                                            <a href="{{ route('reservaciones.index') }}" class="btn btn-secondary">Volver</a>
                                        @endcan
                                    </div>


                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
@endsection
