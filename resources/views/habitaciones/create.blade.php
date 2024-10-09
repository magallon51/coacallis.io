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
        <h1>Agregar Nueva Habitación</h1>

        <form method="POST" action="{{ route('habitaciones.store') }}">
            @csrf


            <div class="form-group">
                <label for="tipo_habitacion">Nuevo Tipo de Habitación:</label>
                <input type="text" class="form-control" id="tipo_habitacion" name="tipo_habitacion" required>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('habitaciones.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
@endsection
