@extends('layouts.coordinador')

@section('title', 'Crear Nueva Materia - Coordinador')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header -->
        <div class="gradient-bg px-4 py-5 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-plus-circle mr-3"></i>
                        {{ isset($materia) ? 'Editar Materia' : 'Crear Nueva Materia' }}
                    </h3>
                    <p class="mt-2 text-deep-teal-200 text-sm">
                        {{ isset($materia) ? 'Modifica los datos académicos de la materia' : 'Registra una nueva materia en el sistema académico' }}
                    </p>
                </div>
                <a href="{{ route('materias.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>

        <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            <form action="{{ isset($materia) ? route('materias.update', $materia->sigla) : route('materias.store') }}" method="POST">
                @csrf
                @if(isset($materia))
                    @method('PUT')
                @endif

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

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Información Académica -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 shadow-sm">
                        <h4 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            Información Académica
                        </h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="sigla" class="block text-sm font-medium text-blue-700 mb-2">
                                    Sigla de la Materia *
                                </label>
                                <input type="text" 
                                       name="sigla" 
                                       id="sigla"
                                       value="{{ old('sigla', $materia->sigla ?? '') }}"
                                       class="w-full px-4 py-3 border border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm"
                                       placeholder="Ej: MAT101"
                                       required
                                       maxlength="10">
                            </div>

                            <div>
                                <label for="nombre" class="block text-sm font-medium text-blue-700 mb-2">
                                    Nombre Completo *
                                </label>
                                <input type="text" 
                                       name="nombre" 
                                       id="nombre"
                                       value="{{ old('nombre', $materia->nombre ?? '') }}"
                                       class="w-full px-4 py-3 border border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm"
                                       placeholder="Nombre completo de la materia"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Configuración Académica -->
                    <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl p-6 border border-emerald-100 shadow-sm">
                        <h4 class="text-lg font-bold text-emerald-800 mb-4 flex items-center">
                            <i class="fas fa-cogs mr-2"></i>
                            Configuración Académica
                        </h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="semestre" class="block text-sm font-medium text-emerald-700 mb-2">
                                    Semestre *
                                </label>
                                <select name="semestre" 
                                        id="semestre"
                                        class="w-full px-4 py-3 border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white shadow-sm"
                                        required>
                                    <option value="">Seleccione el semestre</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" 
                                            {{ old('semestre', $materia->semestre ?? '') == $i ? 'selected' : '' }}>
                                            Semestre {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label for="id_categoria" class="block text-sm font-medium text-emerald-700 mb-2">
                                    Categoría *
                                </label>
                                <select name="id_categoria" 
                                        id="id_categoria"
                                        class="w-full px-4 py-3 border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white shadow-sm"
                                        required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" 
                                            {{ old('id_categoria', $materia->id_categoria ?? '') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="id_carrera" class="block text-sm font-medium text-emerald-700 mb-2">
                                    Carrera *
                                </label>
                                <select name="id_carrera" 
                                        id="id_carrera"
                                        class="w-full px-4 py-3 border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white shadow-sm"
                                        required>
                                    <option value="">Seleccione una carrera</option>
                                    @foreach($carreras as $carrera)
                                        <option value="{{ $carrera->id }}" 
                                            {{ old('id_carrera', $materia->id_carrera ?? '') == $carrera->id ? 'selected' : '' }}>
                                            {{ $carrera->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Académico -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100 shadow-sm mb-8">
                    <h4 class="text-lg font-bold text-purple-800 mb-4 flex items-center">
                        <i class="fas fa-users mr-2"></i>
                        Personal Académico Asignado
                    </h4>
                    <p class="text-purple-600 text-sm mb-4">Selecciona los docentes que impartirán esta materia</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($docentes as $docente)
                            <label class="flex items-center p-4 bg-white rounded-xl border border-purple-100 hover:border-purple-300 transition-all duration-200 cursor-pointer shadow-sm hover:shadow-md">
                                <input type="checkbox" 
                                       name="docentes[]" 
                                       value="{{ $docente->codigo }}"
                                       class="w-5 h-5 text-purple-600 rounded border-purple-300 focus:ring-purple-500 mr-3"
                                       {{ in_array($docente->codigo, old('docentes', $materiaDocentes ?? [])) ? 'checked' : '' }}>
                                <div class="flex-1">
                                    <p class="font-medium text-purple-900">{{ $docente->nombre }}</p>
                                    <p class="text-sm text-purple-600">{{ $docente->codigo }}</p>
                                    <p class="text-xs text-purple-500 mt-1">Disponible</p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @if($docentes->count() === 0)
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-slash text-purple-500 text-2xl"></i>
                            </div>
                            <p class="text-purple-600 font-medium">No hay docentes disponibles</p>
                            <p class="text-purple-500 text-sm">Contacta al administrador para agregar docentes</p>
                        </div>
                    @endif
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end pt-6 border-t border-deep-teal-100">
                    <a href="{{ route('materias.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i>
                        {{ isset($materia) ? 'Actualizar Materia' : 'Crear Materia' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection