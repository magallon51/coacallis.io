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
        <h1 class="text-center mb-4">Agregar Hotel</h1>
        <form method="POST" action="{{ route('hoteles.store') }}">
            @csrf

            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            

            <div class="form-group">
                <label for="caracteristica">Caracteristicas:</label>
                <textarea class="form-control" id="caracteristica" name="caracteristica" rows="4" required></textarea>
            </div>

            <div class="form-group" >
                <label for="servicio">Servicio Habitación:</label>
                <select class="form-control" id="servicio" name="servicio" required>
                <option value="" disabled selected>Selecciona un servicio</option>
                <option value="Internet de alta velocidad, Televisión por cable/satélite, Teléfono en la habitación, Limpieza diaria.">Servicio 1 (Económico)</option>

                <option value="Internet de alta velocidad, Televisión por cable/satélite, Teléfono en la habitación, Limpieza diaria, Cambio de sábanas y toallas, Artículos de baño de alta calidad.">Servicio 2 (Estándar)</option>

                <option value="Internet de alta velocidad, Televisión por cable/satélite, Teléfono en la habitación, Limpieza diaria, Cambio de sábanas y toallas, Artículos de baño de alta calidad, Servicio de habitaciones las 24 horas, Mini bar con bebidas y aperitivos.">Servicio 3 (Premium)</option>

                <option value="Internet de alta velocidad, Televisión por cable/satélite, Teléfono en la habitación, Limpieza diaria, Cambio de sábanas y toallas, Artículos de baño de alta calidad, Servicio de habitaciones las 24 horas, Mini bar con bebidas y aperitivos.">Servicio 4 (Lujo)</option>

                </select>
            </div>

            <div class="form-group">
                <label for="precio_noche">Precio Noche:</label>
                <input type="number" class="form-control" id="precio_noche" name="precio_noche" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono (máximo 10 dígitos):</label>
                <input type="text" class="form-control" id="telefono" name="telefono" maxlength="10" required>
            </div>

            <div class="form-group">
                <label for="id_ubicacion">Selecciona la Ubicación:</label>
                <select class="form-control" name="id_ubicacion" id="id_ubicacion" required>
                    <option value="" disabled selected>Selecciona una ubicacion</option>
                    @foreach($ubicaciones as $ubicacion)
                        <option value="{{ $ubicacion->id_ubicacion }}">{{ $ubicacion->estado }} , {{ $ubicacion->municipio }}</option>
                    @endforeach
                </select>
                @error('id_ubicacion')
                <div class="invalid-feedback" style="display: block">
                    <strong>{{$message}}</strong>
                </div>
                @enderror
            </div>

             <h2 class="mt-4">Asignación de Habitaciones</h2>
            
            <div class="form-group">
            <label for="habitaciones">Ingresa la cantidad de tipo de habitaciones para el hotel:</label>
            
            <div class="row">
                @foreach($habitaciones as $habitacion)
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <input type="hidden" name="habitaciones[{{ $habitacion->id_habitacion }}][id_habitacion]" value="{{ $habitacion->id_habitacion }}" required>
                                
                                <h5 class="card-title">{{ $habitacion->tipo_habitacion }}</h5>
                                
                                <label for="habitaciones[{{ $habitacion->id_habitacion }}][cantidad]">Cantidad:</label>
                                <input type="number" name="habitaciones[{{ $habitacion->id_habitacion }}][cantidad]" value="0" min="0" class="form-control">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

            
            <div class="form-group">
                <label for="imagen" class="form-label">Imagen: </label>
                <input type="file" id="imagen" name="imagen" class="form-control" required>
            </div>

            <div class="form-group">
                <img id="image" src="#" alt="Vista previa de la imagen" style="max-width: 300px; display: none;">
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('hoteles.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
@endsection

