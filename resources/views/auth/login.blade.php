
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url("{{ asset('img/fondo.png') }}");
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .transparent-card {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        .transparent-card-header {
            color: #FFD700;
            font-family: 'Aztec', sans-serif;
        }

        .image-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 20px auto;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #FFD700;
        }

        .image-container img {
            position: absolute;
            top: 55%;
            left: 50%;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: translate(-50%, -50%);
            transition: opacity 1s ease-in-out;
        }

        .image-container img.hidden {
            opacity: 0;
        }

        .transparent-form-label {
            color: #FFD700;
        }

        .transparent-error-message {
            color: #FF5733;
        }

        .transparent-button {
            background-color: #FFD700;
            color: #ffffff;
            border: none;
        }

        .transparent-link {
            color: #ffffff;
            text-align: center;
            display: block;
        }

        @font-face {
            font-family: 'Aztec';
            src: url('font/Aztec.ttf');
        }
    </style>

<div class="transparent-card">
    <div class="card-header fw-bold fs-4 transparent-card-header">{{ __('Inicia Sesión en Coacallis Hotel') }}</div>

    <div class="image-container">
        <img src="{{ asset('img/hombre.png') }}" id="image1" class="visible">
        <img src="{{ asset('img/mujer.png') }}" id="image2" class="hidden">
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label transparent-form-label">{{ __('Correo Electrónico') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="usuario@example.com">

                @error('email')
                <div class="invalid-feedback transparent-error-message" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label transparent-form-label">{{ __('Contraseña') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="contraseña">

                @error('password')
                <div class="invalid-feedback transparent-error-message" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label transparent-form-label" for="remember">{{ __('Recordarme') }}</label>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-block mb-3 transparent-button"><b>{{ __('Iniciar Sesión') }}</b></button>
            </div>

            @if (Route::has('password.request'))
                <a class="btn btn-link transparent-link" href="{{ route('password.request') }}">
                    {{ __('¿Haz olvidado tu contraseña?') }}
                </a>
            @endif

            <a class="btn btn-link transparent-link" href="{{ route('register') }}">
                {{ __('Crear cuenta nueva') }}
            </a>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const images = document.querySelectorAll('.image-container img');
        let currentIndex = 0;

        setInterval(() => {
            images[currentIndex].classList.add('hidden');
            currentIndex = (currentIndex + 1) % images.length;
            images[currentIndex].classList.remove('hidden');
        }, 7000);
    });
</script>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT1mxrCFvCxng31p5j8p3mPpvYW05RM9v9I5hyoXW/HpbiVXss" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.0/dist/umd/popper.min.js" integrity="sha384-7/qFR0X+vFWJ9JqmfAhLVcG+bT8VDjz7aY4bMgN1AYyI8YwqWmkX79WabYOJxz+5" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-vNF7GkHk08FzSOVX7k2Gm/5zO2J/COv3ho8aBddJOk7P1fY6HZ6k1KKkk/s7Y4Ve" crossorigin="anonymous"></script>


