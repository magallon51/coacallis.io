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
        <h1 class="text-center mb-4">Agregar Tarjeta</h1>

        <div class="row">
            <!-- Formulario -->
            <div class="col-md-6">
                <form method="POST" action="{{ route('tarjetas.store') }}" id="tarjetaForm">
                    @csrf
                    <div class="form-group">
                        <label for="nombre">Nombre del titular:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>

                    <div class="form-group">
                        <label for="ap">Apellido Paterno:</label>
                        <input type="text" class="form-control" id="ap" name="ap" required>
                    </div>

                    <div class="form-group">
                        <label for="am">Apellido Materno:</label>
                        <input type="text" class="form-control" id="am" name="am" required>
                    </div>

                    <div class="form-group">
                        <label for="numero">Número de la tarjeta (16-19 dígitos):</label>
                        <input type="text" class="form-control" id="numero" name="numero" required pattern="\d{16,19}">
                        <span id="numero-error" class="text-danger" style="display: none;">El número de tarjeta debe tener al menos 16 dígitos.</span>
                    </div>

                    <div class="form-group">
                        <label for="fecha">Fecha de vencimiento (MM/YY):</label>
                        <input type="text" class="form-control" id="fecha" name="fecha" placeholder="MM/YY" required>
                        @if ($errors->has('fecha'))
                            <span class="text-danger">{{ $errors->first('fecha') }}</span>
                        @endif
                        <span id="fecha-error" class="text-danger" style="display: none;">Su tarjeta al parecer ya no es válida, verifica la fecha de vencimiento.</span>
                        <span id="mes-error" class="text-danger" style="display: none;">El mes debe estar entre 01 y 12.</span>
                    </div>

                    <div class="form-group">
                        <label for="cvc">CVC (3 dígitos):</label>
                        <input type="text" class="form-control" id="cvc" name="cvc" required pattern="\d{3}">
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-primary" id="mostrarVistaPreviaBtn">
                            Aceptar
                        </button>
                        @can ('tarjetas.index')
                            <a href="{{ route('tarjetas.index') }}" class="btn btn-secondary">Volver</a>
                        @endcan
                    </div>
                </form>
            </div>

            <!-- Vista previa oculta -->
            <div class="col-md-6" id="vistaPreviaContainer" style="display: none;">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title m-0"><b>Tarjeta de:</b> <span id="previewNombre"></span> <span id="previewAp"></span> <span id="previewAm"></span></h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><b>Número de Tarjeta:</b> <span id="previewNumero"></span></p>
                        <p class="card-text"><b>Fecha de Vencimiento:</b> <span id="previewFecha"></span></p>
                        <p class="card-text"><b>CVC:</b> <span id="previewCvc"></span></p>
                    </div>
                    <div class="card-footer text-center">
                        <img id="previewBanco" src="" alt="Banco" width="60" class="img-fluid mx-auto">
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-success" id="confirmarBtn">Aceptar y registrar tarjeta</button>
                    <button type="button" class="btn btn-danger" id="cerrarVistaPreviaBtn">Cancelar y cambiar información</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        document.getElementById('fecha').addEventListener('input', function(event)
        {
            let value = event.target.value.replace(/\D/g, '').substring(0, 4);
            if (value.length > 2) {
                value = value.replace(/(\d{2})/, '$1/').substring(0, 5);
            }
            event.target.value = value;
        });

        document.getElementById('cvc').addEventListener('input', function(event)
        {
            event.target.value = event.target.value.replace(/\D/g, '').substring(0, 3);
        });

        document.getElementById('numero').addEventListener('input', function(event)
        {
            event.target.value = event.target.value.replace(/\D/g, '').substring(0, 19);
            const numeroError = document.getElementById('numero-error');
            if (event.target.value.length < 16) {
                numeroError.style.display = 'inline';
            } else {
                numeroError.style.display = 'none';
            }
        });

        document.getElementById('mostrarVistaPreviaBtn').addEventListener('click', function()
        {
            const numero = document.getElementById('numero').value;
            const fecha = document.getElementById('fecha').value;
            const pagarBtn = document.getElementById('mostrarVistaPreviaBtn');
            const fechaError = document.getElementById('fecha-error');
            const mesError = document.getElementById('mes-error');

            const month = parseInt(fecha.substring(0, 2), 10);

            if (numero.length < 16) {
                alert('El número de tarjeta debe tener al menos 16 dígitos.');
                return;
            }

            if (month < 1 || month > 12) {
                mesError.style.display = 'inline';
                return;
            } else {
                mesError.style.display = 'none';
            }

            updatePreview();
            document.getElementById('vistaPreviaContainer').style.display = 'block';
        });

        document.getElementById('confirmarBtn').addEventListener('click', function()
        {
            document.getElementById('tarjetaForm').submit();
        });

        document.getElementById('cerrarVistaPreviaBtn').addEventListener('click', function()
        {
            document.getElementById('vistaPreviaContainer').style.display = 'none';
        });

        document.getElementById('fecha').addEventListener('input', function (event)
        {
            let value = event.target.value.replace(/\D/g, '').substring(0, 4);
            if (value.length > 2) {
                value = value.replace(/(\d{2})/, '$1/').substring(0, 5);
            }
            event.target.value = value;

            const month = parseInt(value.substring(0, 2), 10);
            const year = parseInt('20' + value.substring(3, 5), 10);
            const currentDate = new Date();
            const expiryDate = new Date(year, month - 1); // Months are 0-based in JavaScript

            if (expiryDate < currentDate) {
                document.getElementById('fecha-error').style.display = 'inline';
                document.getElementById('mostrarVistaPreviaBtn').disabled = true;
            } else {
                document.getElementById('fecha-error').style.display = 'none';
                document.getElementById('mostrarVistaPreviaBtn').disabled = false;
            }
        });

        function updatePreview() {
            document.getElementById('previewNombre').textContent = document.getElementById('nombre').value;
            document.getElementById('previewAp').textContent = document.getElementById('ap').value;
            document.getElementById('previewAm').textContent = document.getElementById('am').value;
            document.getElementById('previewNumero').textContent = document.getElementById('numero').value;
            document.getElementById('previewFecha').textContent = document.getElementById('fecha').value;
            document.getElementById('previewCvc').textContent = document.getElementById('cvc').value;

            const banco = document.getElementById('numero').value[0];
            const previewBanco = document.getElementById('previewBanco');
            switch (banco)
            {
                case '1':
                case '2':
                case '3':
                    previewBanco.src = "{{ asset('img/master.png') }}";
                    break;
                case '4':
                case '5':
                case '6':
                    previewBanco.src = "{{ asset('img/visa.png') }}";
                    break;
                case '7':
                case '8':
                case '9':
                    previewBanco.src = "{{ asset('img/american.png') }}";
                    break;
                default:
                    previewBanco.src = "";
                    break;
            }
        }
    </script>
@endsection
