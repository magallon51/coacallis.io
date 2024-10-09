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
        }

        .card-header {
            background-color: #343a40;
            color: #fff;
            padding: 10px;
        }

        .card-body, .card-footer {
            padding: 15px;
        }

        .table {
            margin-top: 20px;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #343a40;
            color: #fff;
        }

        .btn {
            margin-right: 5px;
        }

        .btn-warning, .btn-danger {
            color: #fff;
        }

        .btn-warning:hover, .btn-danger:hover {
            opacity: 0.8;
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
        <h1 class="my-4">Listado de Asignaciones de Habitaciones</h1>

        @can('asignahabitaciones.create')
            <a href="{{ route('asignahabitaciones.create') }}" class="btn btn-primary mb-3">Agregar Nueva Asignación</a>
        @endcan

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Hotel</th>
                <th>Habitación</th>
                <th>Cantidad Disponible</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($asignaciones as $asignacion)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $asignacion->hotel->nombre }}</td>
                    <td>{{ $asignacion->habitacion->tipo_habitacion }}</td>
                    <td>{{ $asignacion->cantidad_habitacion }}</td>
                    <td>
                        @can('asignahabitaciones.edit')
                            <a href="{{ route('asignahabitaciones.edit', $asignacion) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        @endcan

                        @can('asignahabitaciones.destroy')
                            <form action="{{ route('asignahabitaciones.destroy', $asignacion) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Borrar
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
