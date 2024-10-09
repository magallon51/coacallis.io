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
        <h1 class="my-4">Lista de Tickets</h1>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">Agregar Ticket</a>
        <div class="row">
            @forelse($tickets as $ticket)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0"><b>Ticket de: </b>{{ optional($ticket->getreservaciones)->nombre }} {{ optional($ticket->getreservaciones)->ap }} {{ optional($ticket->getreservaciones)->am }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><b>Fecha del Pago:</b> {{ $ticket->fecha_pago }}</p>
                            @if($ticket->getreservaciones)
                                <p class="card-text"><b>Fecha de Reserva:</b> {{ $ticket->getreservaciones->fecha_inicio }} - {{ $ticket->getreservaciones->fecha_fin }}</p>
                            @else
                                <p class="card-text"><b>Fecha de Reserva:</b> No disponible</p>
                            @endif
                            <p class="card-text"><b>Total Pago:</b> ${{ number_format($ticket->precio_total, 2) }}</p>
                            <p class="card-text"><b>Hotel:</b> {{ optional($ticket->gethoteles)->nombre }}</p>
                            <p class="card-text"><b>Número de Cuenta:</b> {{ optional($ticket->gettarjetas)->numero }}</p>
                        </div>
                        <div class="card-footer">
                            <div class="btn-group" role="group" aria-label="Acciones">
                                @can('tickets.edit')
                                    <form action="{{ route('tickets.edit', $ticket) }}" method="GET" style="display:inline;">
                                        <button type="submit" class="btn btn-custom btn-edit mx-1">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                    </form>
                                @endcan
                                @can('tickets.destroy')
                                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-custom btn-delete mx-1">
                                            <i class="fas fa-trash"></i> Borrar
                                        </button>
                                    </form>
                                @endcan
                                        <a href="{{ route('tickets.generatePDF', $ticket->id_ticket) }}" class="btn btn-success btn-download mx-1" target="_blank">
                                            <i class="fas fa-file-pdf"></i> Descargar ticket
                                        </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12">
                    <p>No hay tickets disponibles.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
