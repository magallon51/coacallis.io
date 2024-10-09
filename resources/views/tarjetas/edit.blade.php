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
        <h1>Editar Tarjeta</h1>
        <form method="POST" action="{{ route('tarjetas.update', $tarjeta->id_tarjeta) }}" id="tarjetaForm">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nombre">Nombre del titular:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $tarjeta->nombre }}">
            </div>

            <div class="form-group">
                <label for="ap">Apellido Paterno:</label>
                <input type="text" class="form-control" id="ap" name="ap" value="{{ $tarjeta->ap }}">
            </div>

            <div class="form-group">
                <label for="am">Apellido Materno:</label>
                <input type="text" class="form-control" id="am" name="am" value="{{ $tarjeta->am }}">
            </div>

            <div class="form-group">
                <label for="numero">Número de la tarjeta:</label>
                <input type="text" class="form-control" id="numero" name="numero" value="{{ $tarjeta->numero }}" oninput="limitCardNumber(this)">
            </div>

            <div class="form-group">
                <label for="fecha">Fecha de vencimiento (MM/YY):</label>
                <input type="text" class="form-control" id="fecha" name="fecha" value="{{ $tarjeta->fecha }}" oninput="formatDate(this)">
                <span id="fecha-error" class="text-danger" style="display: none;">Su tarjeta al parecer ya no es válida, verifica la fecha de vencimiento.</span>
            </div>

            <div class="form-group">
                <label for="cvc">CVC:</label>
                <input type="text" class="form-control" id="cvc" name="cvc" value="{{ $tarjeta->cvc }}" oninput="limitCVC(this)">
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary" id="actualizarBtn">Actualizar</button>
                <a href="{{ route('tarjetas.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>

        <!-- Scripts -->
        <script src="{{ asset('js/scripts.js') }}"></script>
        <script>
            document.getElementById('fecha').addEventListener('input', function(event) 
            {
                let value = event.target.value.replace(/\D/g, '').substring(0, 4);
                if (value.length > 2) 
                {
                    value = value.replace(/(\d{2})/, '$1/').substring(0, 5);
                }

                const today = new Date();
                const currentYear = today.getFullYear() % 100; // Get last two digits of the current year

                const inputYear = parseInt(value.substring(3));
                const inputMonth = parseInt(value.substring(0, 2));

                if (inputYear < currentYear || (inputYear === currentYear && inputMonth < (today.getMonth() + 1))) {
                    document.getElementById('fecha-error').style.display = 'inline';
                } else {
                    document.getElementById('fecha-error').style.display = 'none';
                }

                event.target.value = value;
            });

            document.getElementById('cvc').addEventListener('input', function(event) 
            {
                event.target.value = event.target.value.replace(/\D/g, '').substring(0, 3);
            });

            document.getElementById('numero').addEventListener('input', function(event) 
            {
                event.target.value = event.target.value.replace(/\D/g, '').substring(0, 18);
            });
        </script>
    </div>
@endsection
