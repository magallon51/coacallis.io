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
        <h1 class="mb-4">Editar Ubicacion</h1>
        
            <form method="POST" action="{{ route('ubicaciones.update', $ubicacion->id_ubicacion) }}">

            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="estado">Nombre del Estado:</label>
                <input type="text" class="form-control" id="estado" name="estado" value="{{ $ubicacion->estado }}" required>
                @error('estado')
                <span class="invalid-feedback" style="display: block" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="municipio">Nombre del Municipio:</label>
                <input type="text" class="form-control" id="municipio" name="municipio" value="{{ $ubicacion->municipio }}" required>
                @error('municipio')
                <span class="invalid-feedback" style="display: block" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                @enderror
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>

        </form>
        <div class="text-center mt-3">
            <a href="{{ route('ubicaciones.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
@endsection
