<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gestión Docente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'deep-teal': '#012E40',
                        'dark-teal': '#024959',
                        'medium-teal': '#026773',
                        'light-teal': '#3CA6A6',
                        'cream': '#F2E3D5',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #F2E3D5 0%, #ffffff 100%);
            min-height: 100vh;
        }
        
        .stat-card {
            transition: all 0.3s ease;
            border-left: 4px solid;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar {
            background: linear-gradient(180deg, #012E40 0%, #024959 100%);
        }
        
        .nav-item {
            transition: all 0.3s ease;
            border-radius: 8px;
        }
        
        .nav-item:hover {
            background: rgba(60, 166, 166, 0.2);
            transform: translateX(5px);
        }
        
        .nav-item.active {
            background: #3CA6A6;
        }
    </style>
</head>
<body class="flex">
    <!-- Sidebar -->
    <div class="sidebar w-64 min-h-screen text-white p-4 hidden lg:block">
        <div class="text-center mb-8 pt-4">
            <div class="w-16 h-16 bg-light-teal rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-xl font-bold">Sistema Docente</h1>
            <p class="text-light-teal text-sm">FICCT - UAGRM</p>
        </div>

        <nav class="space-y-2">
            <a href="/dashboard" class="nav-item active flex items-center px-4 py-3 text-white">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </a>
            <a href="#" class="nav-item flex items-center px-4 py-3 text-gray-300 hover:text-white">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Docentes
            </a>
            <a href="#" class="nav-item flex items-center px-4 py-3 text-gray-300 hover:text-white">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Materias
            </a>
            <a href="#" class="nav-item flex items-center px-4 py-3 text-gray-300 hover:text-white">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Horarios
            </a>
            <a href="#" class="nav-item flex items-center px-4 py-3 text-gray-300 hover:text-white">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Asistencias
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-4 lg:p-8">
        <!-- Header Mobile -->
        <div class="lg:hidden bg-deep-teal text-white p-4 rounded-lg mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-3 text-light-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <div>
                        <h1 class="text-xl font-bold">Sistema Docente</h1>
                        <p class="text-light-teal text-sm">Dashboard</p>
                    </div>
                </div>
                <button id="menuToggle" class="text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu (Hidden by default) -->
        <div id="mobileMenu" class="lg:hidden hidden bg-white shadow-lg rounded-lg mb-6 p-4">
            <nav class="space-y-2">
                <a href="/dashboard" class="block px-4 py-2 bg-light-teal text-white rounded">Dashboard</a>
                <a href="#" class="block px-4 py-2 text-deep-teal hover:bg-cream rounded">Docentes</a>
                <a href="#" class="block px-4 py-2 text-deep-teal hover:bg-cream rounded">Materias</a>
                <a href="#" class="block px-4 py-2 text-deep-teal hover:bg-cream rounded">Horarios</a>
                <a href="#" class="block px-4 py-2 text-deep-teal hover:bg-cream rounded">Asistencias</a>
            </nav>
        </div>

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-deep-teal">Dashboard</h1>
            <p class="text-dark-teal mt-2">Resumen general del sistema de gestión docente</p>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card bg-white rounded-lg shadow border-l-4 border-light-teal p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-light-teal bg-opacity-10 mr-4">
                        <svg class="w-6 h-6 text-light-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Docentes</p>
                        <p class="text-2xl font-bold text-deep-teal">{{ \App\Models\Docente::count() }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-lg shadow border-l-4 border-medium-teal p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-medium-teal bg-opacity-10 mr-4">
                        <svg class="w-6 h-6 text-medium-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Materias</p>
                        <p class="text-2xl font-bold text-deep-teal">{{ \App\Models\Materia::count() }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-lg shadow border-l-4 border-dark-teal p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-dark-teal bg-opacity-10 mr-4">
                        <svg class="w-6 h-6 text-dark-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Aulas Disponibles</p>
                        <p class="text-2xl font-bold text-deep-teal">{{ \App\Models\Aula::where('estado', 'disponible')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-lg shadow border-l-4 border-deep-teal p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-deep-teal bg-opacity-10 mr-4">
                        <svg class="w-6 h-6 text-deep-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Grupos Activos</p>
                        <p class="text-2xl font-bold text-deep-teal">{{ \App\Models\Grupo::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Rápida -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Gestión Actual -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-deep-teal mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-light-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Gestión Actual
                </h2>
                <div class="space-y-3">
                    @php
                        $gestionActual = \App\Models\GestionAcademica::where('estado', 'curso')->first();
                    @endphp
                    @if($gestionActual)
                    <div class="flex justify-between items-center p-3 bg-cream rounded">
                        <span class="font-medium text-dark-teal">{{ $gestionActual->nombre }}</span>
                        <span class="px-2 py-1 bg-light-teal text-white text-sm rounded">Activa</span>
                    </div>
                    <div class="text-sm text-gray-600">
                        <p>Inicio: {{ \Carbon\Carbon::parse($gestionActual->fecha_inicio)->format('d/m/Y') }}</p>
                        <p>Fin: {{ \Carbon\Carbon::parse($gestionActual->fecha_fin)->format('d/m/Y') }}</p>
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-4">No hay gestión activa</p>
                    @endif
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-deep-teal mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-light-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Acciones Rápidas
                </h2>
                <div class="grid grid-cols-2 gap-3">
                    <a href="#" class="bg-light-teal text-white p-4 rounded-lg text-center hover:bg-medium-teal transition">
                        <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="text-sm">Nuevo Horario</span>
                    </a>
                    <a href="#" class="bg-medium-teal text-white p-4 rounded-lg text-center hover:bg-dark-teal transition">
                        <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm">Registrar Asistencia</span>
                    </a>
                    <a href="#" class="bg-dark-teal text-white p-4 rounded-lg text-center hover:bg-deep-teal transition">
                        <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm">Generar Reporte</span>
                    </a>
                    <a href="#" class="bg-deep-teal text-white p-4 rounded-lg text-center hover:bg-gray-800 transition">
                        <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-sm">Gestionar Docentes</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm mt-12">
            <p>Sistema de Gestión Docente - FICCT UAGRM © 2025</p>
            <p class="mt-1">Desarrollado con Laravel & PostgreSQL</p>
        </div>
    </div>

    <script>
        // Toggle mobile menu
        document.getElementById('menuToggle').addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });

        // Cerrar menú al hacer clic fuera de él
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');
            const toggle = document.getElementById('menuToggle');
            
            if (!menu.contains(event.target) && !toggle.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>