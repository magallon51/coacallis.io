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
            font-size: 20px;
        }

        .card {
            margin-bottom: 20px;
            border: 1px solid #e3e6f0;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,0.15);
        }

        .card-header {
            background-color: #4e73df;
            color: #fff;
            padding: 10px;
            font-size: 18px;
        }

        .card-body {
            padding: 15px;
            background-color: #f8f9fc;
        }

        .card-footer {
            padding: 15px;
            background-color: #f8f9fc;
            text-align: center;
        }

        .btn-custom {
            color: #fff;
            text-decoration: none;
            margin: 5px;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
        }

        .btn-edit {
            background-color: #f6c23e;
            border: none;
        }

        .btn-delete {
            background-color: #e74a3b;
            border: none;
        }

        .card-title {
            font-size: 18px;
        }

        .btn-group {
            display: flex;
            justify-content: center;
        }

        .btn-group form {
            margin: 0;
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
        <h1 class="my-4">Lista de Tarjetas</h1>
        <a href="{{ route('tarjetas.create') }}" class="btn btn-primary mb-3">Agregar Tarjeta</a>
        <div class="row">
            @foreach($tarjetas as $tarjeta)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title m-0"><b>Tarjeta de: </b>{{ $tarjeta->nombre }} {{ $tarjeta->ap }} {{ $tarjeta->am }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><b>Número de Tarjeta:</b> {{ $tarjeta->numero }}</p>
                            <p class="card-text"><b>Fecha de Vencimiento:</b> {{ $tarjeta->fecha }}</p>
                            <p class="card-text"><b>CVC:</b> {{ str_repeat('*', strlen($tarjeta->cvc)) }}</p>
                            <div class="text-center my-3">
                                @php
                                    $firstDigit = substr($tarjeta->numero, 0, 1);
                                @endphp

                                @if (in_array($firstDigit, ['1', '2', '3']))
                                    <img src="{{ asset('img/visa.png') }}" alt="Visa" width="60">
                                @elseif (in_array($firstDigit, ['4', '5', '6']))
                                    <img src="{{ asset('img/master.png') }}" alt="MasterCard" width="60">
                                @elseif (in_array($firstDigit, ['7', '8', '9']))
                                    <img src="{{ asset('img/american.png') }}" alt="American Express" width="60">
                                @else
                                    No especificado
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-group" role="group" aria-label="Acciones">
                                @can('tarjetas.edit')
                                    <form action="{{ route('tarjetas.edit', $tarjeta) }}" method="GET" style="display:inline;">
                                        <button type="submit" class="btn btn-custom btn-edit mx-1">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                    </form>
                                @endcan

                                @can('tarjetas.destroy')
                                    <form action="{{ route('tarjetas.destroy', $tarjeta) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-custom btn-delete mx-1">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
