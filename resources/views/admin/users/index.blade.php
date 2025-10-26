@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-users mr-3"></i>
                    Gestión de Usuarios
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Administra los usuarios del sistema y sus permisos
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.users.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Nuevo Usuario
                </a>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        <!-- Alertas -->
        @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-3 rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3 text-xl"></i>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="text-white hover:text-green-100 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-500 to-rose-600 text-white px-4 py-3 rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                        <span class="font-semibold">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="text-white hover:text-red-100 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Barra de búsqueda -->
        <div class="mb-6 bg-white rounded-2xl p-4 border border-deep-teal-100 shadow-lg">
            <form action="{{ route('admin.users.index') }}" method="GET">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-deep-teal-400"></i>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   class="block w-full pl-10 pr-12 py-3 border border-deep-teal-200 rounded-xl bg-deep-teal-25 placeholder-deep-teal-400 text-deep-teal-800 focus:outline-none focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200"
                                   placeholder="Buscar por nombre o email..." 
                                   value="{{ $search ?? '' }}">
                            @if($search ?? '')
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <a href="{{ route('admin.users.index') }}" class="text-deep-teal-400 hover:text-deep-teal-600 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-[#026773] hover:bg-[#024959] text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-search mr-2"></i>
                        Buscar
                    </button>
                </div>
            </form>
        </div>

        @if($users->count() > 0)
            <!-- Mobile Cards -->
            <div class="block sm:hidden space-y-4">
                @foreach($users as $user)
                <div class="bg-white border border-deep-teal-100 rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white text-lg font-bold shadow-md">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-deep-teal-800">{{ $user->name }}</p>
                                <p class="text-sm text-deep-teal-600">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            @if($user->email_verified_at)
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full border border-green-200">
                                    <i class="fas fa-check-circle mr-1"></i>Verificado
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full border border-yellow-200">
                                    <i class="fas fa-clock mr-1"></i>Pendiente
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-deep-teal-600 text-xs font-medium mb-2">Roles</p>
                        <div class="flex flex-wrap gap-1">
                            @forelse($user->roles as $role)
                                <span class="px-2 py-1 text-xs bg-[#3CA6A6] bg-opacity-20 text-[#026773] rounded-lg border border-[#3CA6A6] border-opacity-30">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-lg">Sin roles</span>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-deep-teal-100">
                        <span class="text-xs text-deep-teal-500 font-medium">
                            <i class="far fa-calendar mr-1"></i>
                            {{ $user->created_at->format('d/m/Y') }}
                        </span>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="inline-flex items-center px-3 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-eye mr-1"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="inline-flex items-center px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-edit mr-1"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('¿Está seguro de eliminar este usuario? Esta acción no se puede deshacer.')"
                                        class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <i class="fas fa-trash mr-1"></i>
                                </button>
                            </form>
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Roles</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Verificación</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Registro</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-deep-teal-50">
                        @foreach($users as $user)
                        <tr class="hover:bg-deep-teal-25 transition-all duration-200">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white font-bold text-lg mr-4 shadow-md">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-deep-teal-800">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-deep-teal-600">
                                            ID: {{ $user->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-deep-teal-800 font-medium">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                        <span class="px-3 py-1 text-xs font-semibold bg-[#3CA6A6] bg-opacity-20 text-[#026773] rounded-xl border border-[#3CA6A6] border-opacity-30">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded-xl">Sin roles</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                @if($user->email_verified_at)
                                    <span class="px-3 py-2 inline-flex text-xs leading-5 font-bold rounded-xl bg-green-100 text-green-800 border border-green-200">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Verificado
                                    </span>
                                @else
                                    <span class="px-3 py-2 inline-flex text-xs leading-5 font-bold rounded-xl bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <i class="fas fa-clock mr-2"></i>
                                        Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-deep-teal-700 font-medium">
                                <i class="far fa-calendar mr-2 text-deep-teal-500"></i>
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-eye mr-2"></i>
                                        Ver
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
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

            <!-- Paginación -->
            <div class="mt-8 flex justify-between items-center">
                <div class="text-sm text-deep-teal-600 font-medium">
                    Mostrando {{ $users->firstItem() }} a {{ $users->lastItem() }} de {{ $users->total() }} usuarios
                </div>
                <div class="bg-white px-6 py-3 rounded-2xl border border-deep-teal-100 shadow-lg">
                    {{ $users->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-deep-teal-100 to-deep-teal-200 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-deep-teal-500 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-deep-teal-700 mb-3">No hay usuarios</h3>
                <p class="text-deep-teal-600 max-w-md mx-auto text-lg mb-8">
                    @if($search ?? '')
                        No se encontraron usuarios para "{{ $search }}"
                    @else
                        No hay usuarios registrados en el sistema.
                    @endif
                </p>
                <a href="{{ route('admin.users.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Primer Usuario
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.gradient-bg {
    background: linear-gradient(135deg, #012E40 0%, #024959 50%, #026773 100%);
}

.bg-deep-teal-25 {
    background-color: rgba(1, 46, 64, 0.025);
}

.border-deep-teal-100 {
    border-color: rgba(1, 46, 64, 0.1);
}

.border-deep-teal-200 {
    border-color: rgba(1, 46, 64, 0.2);
}

.text-deep-teal-200 {
    color: rgba(242, 227, 213, 0.8);
}

.text-deep-teal-400 {
    color: rgba(1, 46, 64, 0.4);
}

.text-deep-teal-500 {
    color: rgba(1, 46, 64, 0.6);
}

.text-deep-teal-600 {
    color: rgba(1, 46, 64, 0.7);
}

.text-deep-teal-700 {
    color: rgba(1, 46, 64, 0.8);
}

.text-deep-teal-800 {
    color: rgba(1, 46, 64, 0.9);
}

.bg-deep-teal-50 {
    background-color: rgba(1, 46, 64, 0.05);
}
</style>
@endsection