@extends('layouts.app')

@section('content')
    <style>
        
        body 
        {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        video 
        {
            position: fixed;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -100;
            transform: translateX(-50%) translateY(-50%);
        }

        .container 
        {
            position: relative;
            z-index: 1;
        }

        .intro 
        {
            background: rgba(0, 0, 0, 0.8); /* Fondo semi-transparente */
            padding: 20px;
            margin: 20px;
            text-align: center;
            border-radius: 10px;
            color: #fff;
        }


        h4 
        {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .team-members 
        {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .team-member 
        {
            flex: 0 0 calc(33.33% - 20px);
            margin: 10px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            background-color: #145f99;
            color: #fff;
            transition: transform 0.3s ease-in-out; /* Agrega una animación de transición */
        }

        .team-member img 
        {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        @font-face
        {
            font-family: 'Aztec';
            src: url('font/Aztec.ttf');
        }

        .titulo1
        {
            font-family: 'Aztec';
            color: #FFD700;
        }


        .team-member h3 
        {
            margin: 0;
            font-size: 18px;
        }

        .reservaciones-title 
        {
            font-size: 48px;
            margin-bottom: 20px;
            text-align: center;
            animation: growAndShrink 2s infinite alternate;
        }

        @keyframes growAndShrink 
        {
            0% {
                font-size: 48px;
            }
            100% {
                font-size: 52px;
            }
        }

        .team-member:hover 
        {
            transform: scale(1.05); /* Escala el elemento en un 5% en el hover */
        }
        h2
        {
            color: #ffff;
        }

    </style>

    <!-- Elimina la etiqueta 'controls' para ocultar los controles y agrega 'autoplay' para reproducir automáticamente -->
    <audio autoplay>
        <source src="{{ asset('music/musica.mp3') }}" type="audio/mpeg">
        Tu navegador no soporta el elemento de audio.
    </audio>

    <div class="overlay"></div>
    <video autoplay muted loop>
        <source src="{{ asset('img/mexico.mp4') }}" type="video/mp4">
        Tu navegador no soporta el elemento de video.
    </video>

    <div class="container">
        <div class="intro">

            <h1 class="titulo1" style="font-size: 70px;">Coacallis</h1>
            <h1 class="titulo1" style="font-size: 40px;">Reservaciones de Hoteles en México</h1>
            <p></p>
            <h4>
                Coacallis es mucho más que un simple servicio de reservaciones de hoteles en México; es una ventana al pasado y una puerta al futuro. El nombre "Coacalli" rinde homenaje a los primeros establecimientos de alojamiento que los aztecas crearon en estas tierras hace siglos. Estos "Coacallis" eran lugares donde la hospitalidad y la comodidad eran fundamentales para los viajeros.
            </h4>
            <p></p>
            <h4>
                Hoy, en plena era digital, Coacallis continúa con esta tradición, pero con una visión moderna. Nuestra aplicación te ofrece la oportunidad de descubrir y reservar los mejores hoteles en México de manera rápida y sencilla, aprovechando las últimas tecnologías. Ya no tienes que preocuparte por encontrar el alojamiento perfecto; Coacallis lo hace por ti. Te invitamos a unirte a nosotros en este emocionante viaje a través de la historia y la innovación. ¡Bienvenido a Coacallis, donde el pasado y el presente se fusionan para brindarte la mejor experiencia de reserva de hoteles en México!
            </h4>
        </div>

        <center><h2>Integrantes del equipo:</h2></center>
        <div class="team-members">
            <div class="team-member">
                <img src="img/patricio.jpeg" />
                <h3><b>Patricio Torrez De Paz</b></h3>
            </div>

            <div class="team-member">
                <img src="img/francisco.jpeg" alt="" />
                <h3><b>Francisco Jesús Magallón Montaño</b></h3>
            </div>

            <div class="team-member">
                <img src="img/ivan.jpeg" />
                <h3><b>Ivan Ortiz Estrada</b></h3>
            </div>

            <div class="team-member">
                <img src="img/may.jpg" />
                <h3><b>Miguel Ángel Sánchez Hernández</b></h3>
            </div>

            <div class="team-member">
                <img src="img/adalberto.jpeg" />
                <h3><b>Adalberto Hernández Perales</b></h3>
            </div>

            <div class="team-member">
                <img src="img/mau.jpeg" />
                <h3><b>Mauricio Estanislao Mercado</b></h3>
            </div>

        </div>
        
    </div>
@endsection
