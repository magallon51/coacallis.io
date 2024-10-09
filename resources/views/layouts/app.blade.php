<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coacallis Reservación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #eae7c8 !important;
            color: #000 !important;
            position: fixed;
            top: 0;
            width: 100%;
            height: 60px;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: #fff;
            flex-shrink: 0;
            position: fixed;
            top: 60px;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 900;
            transition: all 0.3s;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 15px;
            display: block;
            transition: all 0.3s;
            user-select: none;
        }
        .sidebar a:hover {
            background: #34495e;
            transform: translateX(10px);
        }
        .content {
            margin-left: 250px;
            padding-top: 70px;
            padding-left: 20px;
            flex: 1;
            user-select: none;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-md">
    <div class="container">


        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="#">{{ Auth::user()->name }} {{ Auth::user()->ap}} {{ Auth::user()->am }}</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<div class="sidebar">
    @can('ubicaciones.index')
        <a href="{{ route('ubicaciones.index') }}"><i class="fas fa-map-marker-alt"></i> Agregar Ubicación</a>
    @endcan
    @can('habitaciones.index')
        <a href="{{ route('habitaciones.index') }}"><i class="fas fa-bed"></i> Habitaciones</a>
    @endcan
    @can('asignahabitaciones.index')
        <a href="{{ route('asignahabitaciones.index') }}"><i class="fas fa-bed"></i> Asigna Habitaciones</a>
    @endcan
    <a href="{{ route('hoteles.index') }}"><i class="fas fa-hotel"></i> Hoteles<a href="{{ route('reservaciones.index') }}"><i class="fas fa-calendar-check"></i> Consultar Reservaciones</a>
    @can('tarjetas.create')
    <a href="{{ route('tarjetas.create') }}"><i class="fas fa-credit-card"></i> Realizar Pago</a>
    @endcan
    @can('tarjetas.index')
        <a href="{{ route('tarjetas.index') }}"><i class="fas fa-credit-card"></i> Consultar Tarjetas</a>
    @endcan
    <a href="{{ route('tickets.index') }}"><i class="fas fa-ticket-alt"></i> Consultar Tickets</a>
    @can('users.index')
        <a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Consultar Usuarios</a>
    @endcan
    @auth
        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            {{ __('Cerrar Sesión') }}
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    @endauth
</div>
<div class="content">
    <main class="py-4">
        @yield('content')
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
