<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Docentes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-900">Sistema de Gestión Docente</h1>
                <p class="text-gray-600">Panel de Administración</p>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
            <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-3xl font-extrabold tracking-tight">{{ $totalDocentes }}</h3>
                    <p class="text-gray-600 text-sm">Docentes registrados</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-3xl font-extrabold tracking-tight">{{ $totalMaterias }}</h3>
                    <p class="text-gray-600 text-sm">Materias impartidas</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-3xl font-extrabold tracking-tight">{{ $asistenciaHoy }}%</h3>
                    <p class="text-gray-600 text-sm">Asistencia del día</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-3xl font-extrabold tracking-tight">{{ $conflictosHorarios }}</h3>
                    <p class="text-gray-600 text-sm">Conflictos de horario</p>
                </div>
            </div>

            <!-- Estado de aulas -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h2 class="text-xl font-bold mb-6">Estado de Aulas</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-2xl font-bold text-green-600">{{ $estadoAulas['disponibles'] }}</p>
                        <p class="text-green-800">Disponibles</p>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600">{{ $estadoAulas['en_uso'] }}</p>
                        <p class="text-blue-800">En uso</p>
                    </div>
                    <div class="text-center p-4 bg-amber-50 rounded-lg">
                        <p class="text-2xl font-bold text-amber-600">{{ $estadoAulas['mantenimiento'] }}</p>
                        <p class="text-amber-800">Mantenimiento</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>