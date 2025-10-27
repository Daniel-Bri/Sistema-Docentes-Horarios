@extends('layouts.coordinador')

@section('title', 'Asignar Grupo - Coordinador')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header -->
        <div class="gradient-bg px-4 py-5 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-users-cog mr-3"></i>
                        Asignar Grupo a Materia
                    </h3>
                    <p class="mt-2 text-deep-teal-200 text-sm">
                        Programa grupos y horarios para: <strong>{{ $materia->sigla }} - {{ $materia->nombre }}</strong>
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('materias.show', $materia->sigla) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            <form action="{{ route('materias.store-asignacion-grupo', $materia->sigla) }}" method="POST" id="asignacionForm">
                @csrf

                @if($errors->any())
                    <div class="mb-6 bg-rose-50 border border-rose-200 rounded-2xl p-5">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-rose-500 rounded-full flex items-center justify-center text-white mr-3">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h4 class="text-lg font-bold text-rose-800">Corrige los siguientes errores</h4>
                        </div>
                        <ul class="list-disc list-inside text-rose-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Información de la Materia -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 shadow-sm mb-8">
                    <h4 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Información de la Materia
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                {{ substr($materia->sigla, 0, 2) }}
                            </div>
                            <p class="font-bold text-blue-900">{{ $materia->sigla }}</p>
                            <p class="text-sm text-blue-700">Sigla</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                S{{ $materia->semestre }}
                            </div>
                            <p class="font-bold text-green-900">Semestre {{ $materia->semestre }}</p>
                            <p class="text-sm text-green-700">Nivel</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <p class="font-bold text-purple-900">{{ $materia->docentes->count() }}</p>
                            <p class="text-sm text-purple-700">Docentes</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Configuración del Grupo -->
                    <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl p-6 border border-emerald-100 shadow-sm">
                        <h4 class="text-lg font-bold text-emerald-800 mb-4 flex items-center">
                            <i class="fas fa-cogs mr-2"></i>
                            Configuración del Grupo
                        </h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="id_grupo" class="block text-sm font-medium text-emerald-700 mb-2">
                                    Grupo Académico *
                                </label>
                                <select name="id_grupo" 
                                        id="id_grupo"
                                        class="w-full px-4 py-3 border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white shadow-sm"
                                        required>
                                    <option value="">Seleccione un grupo</option>
                                    @foreach($grupos as $grupo)
                                        <option value="{{ $grupo->id }}" {{ old('id_grupo') == $grupo->id ? 'selected' : '' }}>
                                            {{ $grupo->nombre }} - {{ $grupo->descripcion ?? 'Grupo académico' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="id_gestion" class="block text-sm font-medium text-emerald-700 mb-2">
                                    Gestión Académica *
                                </label>
                                <select name="id_gestion" 
                                        id="id_gestion"
                                        class="w-full px-4 py-3 border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white shadow-sm"
                                        required>
                                    <option value="">Seleccione una gestión</option>
                                    @foreach($gestiones as $gestion)
                                        <option value="{{ $gestion->id }}" {{ old('id_gestion') == $gestion->id ? 'selected' : '' }}>
                                            {{ $gestion->gestion }} 
                                            @if($gestion->estado == 'activa')
                                                <span class="text-green-600">(Activa)</span>
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Recursos Disponibles -->
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-100 shadow-sm">
                        <h4 class="text-lg font-bold text-amber-800 mb-4 flex items-center">
                            <i class="fas fa-tools mr-2"></i>
                            Recursos Disponibles
                        </h4>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-amber-200">
                                <div>
                                    <p class="font-medium text-amber-900">Docentes Habilitados</p>
                                    <p class="text-sm text-amber-700">{{ $docentes->count() }} profesionales</p>
                                </div>
                                <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-amber-200">
                                <div>
                                    <p class="font-medium text-amber-900">Aulas Disponibles</p>
                                    <p class="text-sm text-amber-700" id="aulas-count">Cargando...</p>
                                </div>
                                <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-door-open"></i>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-amber-200">
                                <div>
                                    <p class="font-medium text-amber-900">Horarios Configurados</p>
                                    <p class="text-sm text-amber-700" id="horarios-count">Cargando...</p>
                                </div>
                                <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Programación de Horarios -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100 shadow-sm mb-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <h4 class="text-lg font-bold text-purple-800 flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Programación de Horarios
                        </h4>
                        <button type="button" id="add-horario" 
                                class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 mt-4 sm:mt-0">
                            <i class="fas fa-plus mr-2"></i>
                            Agregar Horario
                        </button>
                    </div>

                    <p class="text-purple-600 text-sm mb-6">
                        Configura los horarios académicos para este grupo. Puedes agregar múltiples horarios según sea necesario.
                    </p>

                    <div id="horarios-container" class="space-y-4">
                        <!-- Horario inicial -->
                        <div class="horario-item bg-white rounded-2xl p-5 border border-purple-200 shadow-sm">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                                <h5 class="text-md font-bold text-purple-900 flex items-center">
                                    <i class="fas fa-clock mr-2 text-purple-600"></i>
                                    Horario #1
                                </h5>
                                <button type="button" class="remove-horario inline-flex items-center px-3 py-1 bg-rose-500 hover:bg-rose-600 text-white text-sm rounded-lg transition-all duration-200 mt-2 sm:mt-0">
                                    <i class="fas fa-times mr-1"></i>
                                    Eliminar
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-purple-700 mb-2">
                                        Horario Académico *
                                    </label>
                                    <select name="horarios[0][id_horario]" 
                                            class="horario-select w-full px-3 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                            required>
                                        <option value="">Seleccione horario</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-purple-700 mb-2">
                                        Docente Responsable *
                                    </label>
                                    <select name="horarios[0][codigo_docente]" 
                                            class="w-full px-3 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                            required>
                                        <option value="">Seleccione docente</option>
                                        @foreach($docentes as $docente)
                                            <option value="{{ $docente->codigo }}">
                                                {{ $docente->nombre }} ({{ $docente->codigo }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-purple-700 mb-2">
                                        Aula Designada *
                                    </label>
                                    <select name="horarios[0][id_aula]" 
                                            class="aula-select w-full px-3 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                            required>
                                        <option value="">Seleccione aula</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estado de la Programación -->
                    <div id="estado-programacion" class="mt-6 p-4 bg-white rounded-xl border border-purple-200 hidden">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white mr-3">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-800" id="estado-texto">Programación completa</p>
                                <p class="text-sm text-green-600" id="estado-descripcion">Todos los horarios están correctamente configurados</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumen y Confirmación -->
                <div class="bg-gradient-to-br from-cyan-50 to-blue-50 rounded-2xl p-6 border border-cyan-100 shadow-sm mb-8">
                    <h4 class="text-lg font-bold text-cyan-800 mb-4 flex items-center">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        Resumen de la Asignación
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h5 class="font-semibold text-cyan-700 mb-3">Configuración Actual</h5>
                            <dl class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-cyan-600">Materia:</dt>
                                    <dd class="font-medium text-cyan-800">{{ $materia->sigla }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-cyan-600">Grupo:</dt>
                                    <dd class="font-medium text-cyan-800" id="resumen-grupo">-</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-cyan-600">Gestión:</dt>
                                    <dd class="font-medium text-cyan-800" id="resumen-gestion">-</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-cyan-600">Horarios:</dt>
                                    <dd class="font-medium text-cyan-800" id="resumen-horarios">0</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h5 class="font-semibold text-cyan-700 mb-3">Verificación del Sistema</h5>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-center text-green-600">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Materia habilitada
                                </li>
                                <li class="flex items-center text-amber-600" id="verif-grupo">
                                    <i class="fas fa-clock mr-2"></i>
                                    Selecciona un grupo
                                </li>
                                <li class="flex items-center text-amber-600" id="verif-horarios">
                                    <i class="fas fa-clock mr-2"></i>
                                    Configura horarios
                                </li>
                                <li class="flex items-center text-amber-600" id="verif-recursos">
                                    <i class="fas fa-clock mr-2"></i>
                                    Verifica recursos
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end pt-6 border-t border-deep-teal-100">
                    <a href="{{ route('materias.show', $materia->sigla) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            id="btn-submit"
                            class="inline-flex items-center justify-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                        <i class="fas fa-calendar-check mr-2"></i>
                        Confirmar Programación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let horarioCount = 1;

// Cargar recursos al iniciar
$(document).ready(function() {
    cargarRecursos();
    actualizarResumen();
});

// Cargar horarios y aulas disponibles
function cargarRecursos() {
    // Cargar horarios
    $.get('{{ route("materias.get-horarios") }}', function(horarios) {
        $('.horario-select').each(function() {
            const currentVal = $(this).val();
            $(this).empty().append('<option value="">Seleccione horario</option>');
            horarios.forEach(function(horario) {
                const option = `<option value="${horario.id}">
                    ${horario.dia} - ${horario.hora_inicio} a ${horario.hora_fin}
                </option>`;
                $(this).append(option);
            });
            if (currentVal) $(this).val(currentVal);
        });
        $('#horarios-count').text(horarios.length + ' disponibles');
    });

    // Cargar aulas
    $.get('{{ route("materias.get-aulas") }}', function(aulas) {
        $('.aula-select').each(function() {
            const currentVal = $(this).val();
            $(this).empty().append('<option value="">Seleccione aula</option>');
            aulas.forEach(function(aula) {
                const option = `<option value="${aula.id}">
                    ${aula.nombre} (Cap: ${aula.capacidad})
                </option>`;
                $(this).append(option);
            });
            if (currentVal) $(this).val(currentVal);
        });
        $('#aulas-count').text(aulas.length + ' disponibles');
    });
}

// Agregar nuevo horario
$('#add-horario').click(function() {
    horarioCount++;
    const newHorario = `
        <div class="horario-item bg-white rounded-2xl p-5 border border-purple-200 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                <h5 class="text-md font-bold text-purple-900 flex items-center">
                    <i class="fas fa-clock mr-2 text-purple-600"></i>
                    Horario #${horarioCount}
                </h5>
                <button type="button" class="remove-horario inline-flex items-center px-3 py-1 bg-rose-500 hover:bg-rose-600 text-white text-sm rounded-lg transition-all duration-200 mt-2 sm:mt-0">
                    <i class="fas fa-times mr-1"></i>
                    Eliminar
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-purple-700 mb-2">
                        Horario Académico *
                    </label>
                    <select name="horarios[${horarioCount-1}][id_horario]" 
                            class="horario-select w-full px-3 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                            required>
                        <option value="">Seleccione horario</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-purple-700 mb-2">
                        Docente Responsable *
                    </label>
                    <select name="horarios[${horarioCount-1}][codigo_docente]" 
                            class="w-full px-3 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                            required>
                        <option value="">Seleccione docente</option>
                        @foreach($docentes as $docente)
                            <option value="{{ $docente->codigo }}">
                                {{ $docente->nombre }} ({{ $docente->codigo }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-purple-700 mb-2">
                        Aula Designada *
                    </label>
                    <select name="horarios[${horarioCount-1}][id_aula]" 
                            class="aula-select w-full px-3 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                            required>
                        <option value="">Seleccione aula</option>
                    </select>
                </div>
            </div>
        </div>
    `;
    $('#horarios-container').append(newHorario);
    cargarRecursos();
    actualizarResumen();
});

// Eliminar horario
$(document).on('click', '.remove-horario', function() {
    if ($('.horario-item').length > 1) {
        $(this).closest('.horario-item').remove();
        // Renumerar horarios
        $('.horario-item').each(function(index) {
            $(this).find('h5').html(`<i class="fas fa-clock mr-2 text-purple-600"></i> Horario #${index + 1}`);
        });
        horarioCount--;
        actualizarResumen();
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Horario requerido',
            text: 'Debe haber al menos un horario configurado',
            confirmButtonColor: '#3CA6A6'
        });
    }
});

// Actualizar resumen en tiempo real
function actualizarResumen() {
    const grupo = $('#id_grupo option:selected').text();
    const gestion = $('#id_gestion option:selected').text();
    const horariosCount = $('.horario-item').length;

    // Actualizar resumen
    $('#resumen-grupo').text(grupo || '-');
    $('#resumen-gestion').text(gestion || '-');
    $('#resumen-horarios').text(horariosCount);

    // Verificar estado
    const grupoSeleccionado = $('#id_grupo').val();
    const gestionSeleccionada = $('#id_gestion').val();
    const horariosCompletos = verificarHorariosCompletos();

    // Actualizar verificaciones
    $('#verif-grupo').html(grupoSeleccionado ? 
        '<i class="fas fa-check-circle mr-2"></i> Grupo seleccionado' :
        '<i class="fas fa-clock mr-2"></i> Selecciona un grupo'
    ).toggleClass('text-green-600 text-amber-600', grupoSeleccionado);

    $('#verif-horarios').html(horariosCompletos ? 
        `<i class="fas fa-check-circle mr-2"></i> ${horariosCount} horarios configurados` :
        '<i class="fas fa-clock mr-2"></i> Configura horarios'
    ).toggleClass('text-green-600 text-amber-600', horariosCompletos);

    $('#verif-recursos').html(horariosCompletos ? 
        '<i class="fas fa-check-circle mr-2"></i> Recursos verificados' :
        '<i class="fas fa-clock mr-2"></i> Verifica recursos'
    ).toggleClass('text-green-600 text-amber-600', horariosCompletos);

    // Habilitar/deshabilitar submit
    const formularioCompleto = grupoSeleccionado && gestionSeleccionada && horariosCompletos;
    $('#btn-submit').prop('disabled', !formularioCompleto);

    // Mostrar/ocultar estado
    if (formularioCompleto) {
        $('#estado-programacion').removeClass('hidden');
        $('#estado-texto').text('Programación completa y verificada');
        $('#estado-descripcion').text('Todos los requisitos están satisfechos. Puede confirmar la programación.');
    } else {
        $('#estado-programacion').addClass('hidden');
    }
}

// Verificar que todos los horarios estén completos
function verificarHorariosCompletos() {
    let todosCompletos = true;
    $('.horario-item').each(function() {
        const horario = $(this).find('.horario-select').val();
        const docente = $(this).find('select[name*="codigo_docente"]').val();
        const aula = $(this).find('.aula-select').val();
        
        if (!horario || !docente || !aula) {
            todosCompletos = false;
            return false;
        }
    });
    return todosCompletos;
}

// Event listeners para cambios en el formulario
$('#id_grupo, #id_gestion').change(actualizarResumen);
$(document).on('change', '.horario-select, .aula-select, select[name*="codigo_docente"]', actualizarResumen);

// Validación antes de enviar
$('#asignacionForm').on('submit', function(e) {
    if (!verificarHorariosCompletos()) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Horarios incompletos',
            text: 'Por favor, complete todos los campos de horarios antes de enviar',
            confirmButtonColor: '#3CA6A6'
        });
    }
});
</script>
@endpush