<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal</title>
</head>
<body class="p-6">
    <h1>Bienvenido, {{ Auth::user()->nombre }}</h1>
    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>
</body>
</html>
