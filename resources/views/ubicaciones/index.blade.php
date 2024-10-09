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
        <h1 class="my-4">Lista de Ubicaciones</h1>

        <a href="{{ route('ubicaciones.create') }}" class="btn btn-primary mb-3">Agregar Nueva Ubicación</a>

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Estado</th>
                <th>Municipio</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($ubicaciones as $ubicacion)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $ubicacion->estado }}</td>
                    <td>{{ $ubicacion->municipio }}</td>
                    <td>
                        @can('ubicaciones.edit')
                            <a href="{{ route('ubicaciones.edit', $ubicacion) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        @endcan

                        @can('ubicaciones.destroy')
                            <form action="{{ route('ubicaciones.destroy', $ubicacion) }}" method="POST" style="display:inline;">
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
