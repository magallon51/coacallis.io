<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Comprobante</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .ticket {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-rows: auto 1fr auto;
            gap: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #2c3e50;
        }
        .content {
            padding: 10px 0;
            line-height: 1.8;
        }
        .content p {
            margin: 5px 0;
        }
        .content p strong {
            display: inline-block;
            width: 150px;
        }
        .content p span {
            display: inline-block;
            width: calc(100% - 150px);
        }
        .footer {
            text-align: center;
            border-top: 2px solid #2c3e50;
            padding-top: 10px;
            color: #777;
        }
    </style>
</head>
<body>
@forelse($tickets as $ticket)
    <div class="ticket">
        <div class="header">
            <img style="width: 70px; height: 70px" src="{{ public_path('img/logo.png') }}" alt="Logo">
            <h2 >Ticket de Reservación</h2>
        </div>
        <div class="content">
            <p><strong>Nombre:</strong> <span>{{ optional($ticket->getreservaciones)->nombre }} {{ optional($ticket->getreservaciones)->ap }} {{ optional($ticket->getreservaciones)->am }}</span></p>
            <p><strong>Fecha del Pago:</strong> <span>{{ $ticket->fecha_pago }}</span></p>
            <p><strong>Fecha de Reserva:</strong> <span>{{ optional($ticket->getreservaciones)->fecha_inicio }} - {{ $ticket->getreservaciones->fecha_fin }}</span></p>
            <p><strong>Total Pago:</strong> <span>${{ number_format($ticket->precio_total, 2) }}</span></p>
            <p><strong>Hotel:</strong> <span>{{ optional($ticket->gethoteles)->nombre }}</span></p>
            <p><strong>Número de Cuenta:</strong> <span>{{ optional($ticket->gettarjetas)->numero }}</span></p>
        </div>
        <div class="footer">
            <p>Gracias por reservar en Coacallis</p>
        </div>
    </div>
@empty
    <p>No hay tickets disponibles.</p>
@endforelse
</body>
</html>
