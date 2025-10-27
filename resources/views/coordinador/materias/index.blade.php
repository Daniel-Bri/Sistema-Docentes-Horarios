@extends('layouts.coordinador')

@section('title', 'Gestión de Materias - Coordinador')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-book-open mr-3"></i>
                    Gestión Académica de Materias
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Administra las materias de tu área académica
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('coordinador.materias.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Nueva Materia
                </a>
                <button onclick="exportarReporte()"
                        class="inline-flex items-center px-4 py-2 bg-[#024959] hover:bg-[#012E40] border border-transparent rounded-xl font-semibold text-xs text-[#F2E3D5] uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Reporte
                </button>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        <!-- Filtros Rápidos -->
        <div class="mb-6 bg-white rounded-2xl p-4 border border-deep-teal-100 shadow-sm">
            <div class="flex flex-col sm:flex-row gap-4 items-center">
                <div class="flex-1">
                    <input type="text" 
                           id="searchInput"
                           placeholder="Buscar materia por sigla o nombre..."
                           class="w-full px-4 py-2 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-[#3CA6A6] transition-all duration-200">
                </div>
                <div class="flex gap-2">
                    <select id="filterSemestre" class="px-3 py-2 border border-deep-teal-200 rounded-xl text-sm focus:ring-2 focus:ring-[#3CA6A6]">
                        <option value="">Todos los semestres</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">Semestre {{ $i }}</option>
                        @endfor
                    </select>
                    <select id="filterEstado" class="px-3 py-2 border border-deep-teal-200 rounded-xl text-sm focus:ring-2 focus:ring-[#3CA6A6]">
                        <option value="">Todos los estados</option>
                        <option value="con-grupos">Con grupos</option>
                        <option value="sin-grupos">Sin grupos</option>
                    </select>
                </div>
            </div>
        </div>

        @if($materias->count() > 0)
            <!-- Estadísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-4 border border-blue-100 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-600 text-sm font-medium">Total Materias</p>
                            <p class="text-2xl font-bold text-blue-800">{{ $materias->count() }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-4 border border-green-100 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-600 text-sm font-medium">Con Grupos</p>
                            <p class="text-2xl font-bold text-green-800">
                                {{ $materias->where('grupoMaterias.count', '>', 0)->count() }}
                            </p>
                        </div>
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-4 border border-amber-100 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-amber-600 text-sm font-medium">Sin Grupos</p>
                            <p class="text-2xl font-bold text-amber-800">
                                {{ $materias->where('grupoMaterias.count', 0)->count() }}
                            </p>
                        </div>
                        <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-user-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-4 border border-purple-100 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-600 text-sm font-medium">Grupos Totales</p>
                            <p class="text-2xl font-bold text-purple-800">
                                {{ $materias->sum('grupoMaterias.count') }}
                            </p>
                        </div>
                        <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Cards -->
            <div class="block sm:hidden space-y-4">
                @foreach($materias as $materia)
                <div class="bg-white border border-deep-teal-100 rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 materia-card">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md">
                                    {{ substr($materia->sigla, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-bold text-deep-teal-800 text-sm materia-sigla">{{ $materia->sigla }}</p>
                                    <p class="text-xs text-deep-teal-600 materia-nombre">{{ $materia->nombre }}</p>
                                </div>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full border shadow-sm bg-green-100 text-green-800 border-green-200 materia-semestre">
                            S{{ $materia->semestre }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                        <div>
                            <p class="text-deep-teal-600 text-xs font-medium">Categoría</p>
                            <p class="font-bold text-deep-teal-800">{{ $materia->categoria->nombre ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-deep-teal-600 text-xs font-medium">Carrera</p>
                            <p class="font-bold text-deep-teal-800">{{ $materia->carrera->nombre ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Grupos Asignados -->
                    <div class="mb-4">
                        <p class="text-deep-teal-600 text-xs font-medium mb-2">Grupos Activos</p>
                        <div class="flex items-center justify-between">
                            <div class="flex flex-wrap gap-1">
                                @if($materia->grupoMaterias->count() > 0)
                                    @foreach($materia->grupoMaterias->take(2) as $grupoMateria)
                                        <span class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg border border-blue-100">
                                            {{ $grupoMateria->grupo->nombre ?? 'N/A' }}
                                        </span>
                                    @endforeach
                                    @if($materia->grupoMaterias->count() > 2)
                                        <span class="inline-flex items-center px-2 py-1 bg-gray-50 text-gray-600 text-xs rounded-lg border border-gray-100">
                                            +{{ $materia->grupoMaterias->count() - 2 }}
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2 py-1 bg-amber-50 text-amber-600 text-xs rounded-lg border border-amber-100">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Sin grupos
                                    </span>
                                @endif
                            </div>
                            <span class="text-xs font-medium {{ $materia->grupoMaterias->count() > 0 ? 'text-green-600' : 'text-amber-600' }}">
                                {{ $materia->grupoMaterias->count() }} grupo(s)
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-deep-teal-100">
                        <span class="text-xs text-deep-teal-500 font-medium materia-estado">
                            @if($materia->grupoMaterias->count() > 0)
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                Activa
                            @else
                                <i class="fas fa-clock text-amber-500 mr-1"></i>
                                Pendiente
                            @endif
                        </span>
                        <div class="flex gap-2">
                            <a href="{{ route('coordinador.materias.show', $materia->sigla) }}" 
                               class="inline-flex items-center px-3 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-eye mr-2"></i>
                                Ver
                            </a>
                            <a href="{{ route('coordinador.materias.asignar-grupo', $materia->sigla) }}" 
                               class="inline-flex items-center px-3 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-users mr-2"></i>
                                Grupos
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Desktop Table -->
            <div class="hidden sm:block overflow-x-auto rounded-2xl border border-deep-teal-100 shadow-lg">
                <table class="min-w-full divide-y divide-deep-teal-100">
                    <thead class="bg-gradient-to-r from-[#012E40] to-[#024959]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Materia</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Semestre</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Categoría</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Carrera</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Grupos</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-deep-teal-50" id="materiasTable">
                        @foreach($materias as $materia)
                        <tr class="hover:bg-deep-teal-25 transition-all duration-200 materia-row" 
                            data-sigla="{{ $materia->sigla }}"
                            data-nombre="{{ $materia->nombre }}"
                            data-semestre="{{ $materia->semestre }}"
                            data-grupos="{{ $materia->grupoMaterias->count() }}">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white font-bold text-lg mr-4 shadow-md">
                                        {{ substr($materia->sigla, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-deep-teal-800 materia-sigla">
                                            {{ $materia->sigla }}
                                        </div>
                                        <div class="text-sm text-deep-teal-600 max-w-xs truncate materia-nombre">
                                            {{ $materia->nombre }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-4 py-2 inline-flex text-xs leading-5 font-bold rounded-xl border shadow-sm bg-blue-100 text-blue-800 border-blue-200 materia-semestre">
                                    Semestre {{ $materia->semestre }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-deep-teal-800">
                                {{ $materia->categoria->nombre ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-deep-teal-800">
                                {{ $materia->carrera->nombre ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-lg 
                                    {{ $materia->grupoMaterias->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }} materia-grupos-count">
                                    {{ $materia->grupoMaterias->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $materia->grupoMaterias->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }} materia-estado">
                                    <i class="fas {{ $materia->grupoMaterias->count() > 0 ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                                    {{ $materia->grupoMaterias->count() > 0 ? 'Activa' : 'Pendiente' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('materias.show', $materia->sigla) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-eye mr-2"></i>
                                        Detalles
                                    </a>
                                    <a href="{{ route('materias.asignar-grupo', $materia->sigla) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-users mr-2"></i>
                                        Grupos
                                    </a>
                                    <a href="{{ route('materias.edit', $materia->sigla) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-edit mr-2"></i>
                                        Editar
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-deep-teal-100 to-deep-teal-200 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-book-open text-deep-teal-500 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-deep-teal-700 mb-3">No hay materias asignadas</h3>
                <p class="text-deep-teal-600 max-w-md mx-auto text-lg mb-6">
                    Comienza registrando las materias de tu área académica.
                </p>
                <a href="{{ route('materias.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Registrar Primera Materia
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function exportarReporte() {
    alert('Generando reporte de materias...');
}

// Filtrado en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterSemestre = document.getElementById('filterSemestre');
    const filterEstado = document.getElementById('filterEstado');
    
    function filterMaterias() {
        const searchTerm = searchInput.value.toLowerCase();
        const semestreFilter = filterSemestre.value;
        const estadoFilter = filterEstado.value;
        
        document.querySelectorAll('.materia-row, .materia-card').forEach(element => {
            let show = true;
            
            // Filtro de búsqueda
            if (searchTerm) {
                const sigla = element.getAttribute('data-sigla') || 
                             element.querySelector('.materia-sigla')?.textContent.toLowerCase();
                const nombre = element.getAttribute('data-nombre') || 
                              element.querySelector('.materia-nombre')?.textContent.toLowerCase();
                
                if (!sigla.includes(searchTerm) && !nombre.includes(searchTerm)) {
                    show = false;
                }
            }
            
            // Filtro por semestre
            if (semestreFilter && show) {
                const semestre = element.getAttribute('data-semestre') || 
                               element.querySelector('.materia-semestre')?.textContent.match(/\d+/)?.[0];
                if (semestre !== semestreFilter) {
                    show = false;
                }
            }
            
            // Filtro por estado
            if (estadoFilter && show) {
                const grupos = parseInt(element.getAttribute('data-grupos') || 
                                      element.querySelector('.materia-grupos-count')?.textContent || 0);
                
                if (estadoFilter === 'con-grupos' && grupos === 0) {
                    show = false;
                } else if (estadoFilter === 'sin-grupos' && grupos > 0) {
                    show = false;
                }
            }
            
            element.style.display = show ? '' : 'none';
        });
    }
    
    searchInput.addEventListener('input', filterMaterias);
    filterSemestre.addEventListener('change', filterMaterias);
    filterEstado.addEventListener('change', filterMaterias);
});
</script>
@endpush
@endsection