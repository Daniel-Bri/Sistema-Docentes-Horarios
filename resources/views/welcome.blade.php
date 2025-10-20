<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Docentes y Horarios</title>
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #3490dc, #6cb2eb);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background-color: white;
            padding: 40px 20px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            max-width: 450px;
            width: 100%;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 25px;
            color: #333;
        }

        p {
            margin-bottom: 30px;
            color: #555;
        }

        a, button {
            display: inline-block;
            margin: 5px;
            padding: 10px 25px;
            font-size: 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
        }

        a {
            background-color: #3490dc;
            color: white;
        }

        a:hover {
            background-color: #2779bd;
        }

        button {
            background-color: #e3342f;
            color: white;
        }

        button:hover {
            background-color: #cc1f1a;
        }

        @media (max-width: 500px) {
            .container {
                padding: 30px 15px;
            }

            h1 {
                font-size: 20px;
            }

            a, button {
                font-size: 14px;
                padding: 8px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sistema de Gestión de Docentes y Horarios</h1>

        @auth
            <p>Bienvenido, <strong>{{ Auth::user()->nombre }}</strong></p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
            <a href="{{ url('/dashboard') }}">Ir al panel</a>
        @else
            <p>Bienvenido al sistema. Por favor, inicia sesión para continuar.</p>
            <a href="{{ route('login') }}">Iniciar sesión</a>
            <a href="{{ route('register') }}">Registrarse</a>
        @endauth
    </div>
</body>
</html>
