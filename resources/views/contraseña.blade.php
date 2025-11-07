@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="container mx-auto px-3 max-w-md">
        <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
            <!-- Header -->
            <div class="gradient-bg px-4 py-5 sm:px-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-xl sm:text-2xl font-bold text-[#F2E3D5]">
                            <i class="fas fa-lock mr-2"></i>
                            Cambiar Contraseña
                        </h3>
                        <p class="mt-1 sm:mt-2 text-deep-teal-200 text-sm">
                            Actualice su contraseña de acceso al sistema
                        </p>
                    </div>
                    <a href="{{ url()->previous() }}" 
                       class="inline-flex items-center px-3 sm:px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-lg sm:rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm w-full sm:w-auto justify-center">
                        <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>
                        <span class="text-xs sm:text-sm">Volver</span>
                    </a>
                </div>
            </div>

            <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
                <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-deep-teal-100 shadow-lg">
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Contraseña Actual -->
                        <div class="mb-6">
                            <label for="current_password" class="block text-sm font-bold text-deep-teal-800 mb-2">
                                <i class="fas fa-lock mr-2 text-[#3CA6A6]"></i>
                                Contraseña Actual *
                            </label>
                            <input type="password" 
                                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-deep-teal-200 rounded-lg sm:rounded-xl bg-deep-teal-25 placeholder-deep-teal-400 text-deep-teal-800 focus:outline-none focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 @error('current_password') border-red-500 @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   placeholder="Ingrese su contraseña actual"
                                   required>
                            @error('current_password')
                                <div class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Nueva Contraseña -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-bold text-deep-teal-800 mb-2">
                                <i class="fas fa-key mr-2 text-[#3CA6A6]"></i>
                                Nueva Contraseña *
                            </label>
                            <input type="password" 
                                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-deep-teal-200 rounded-lg sm:rounded-xl bg-deep-teal-25 placeholder-deep-teal-400 text-deep-teal-800 focus:outline-none focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Ingrese nueva contraseña"
                                   required>
                            @error('password')
                                <div class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-bold text-deep-teal-800 mb-2">
                                <i class="fas fa-key mr-2 text-[#3CA6A6]"></i>
                                Confirmar Contraseña *
                            </label>
                            <input type="password" 
                                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-deep-teal-200 rounded-lg sm:rounded-xl bg-deep-teal-25 placeholder-deep-teal-400 text-deep-teal-800 focus:outline-none focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Confirme la nueva contraseña"
                                   required>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4 sm:pt-6 border-t border-deep-teal-100">
                            <button type="submit" 
                                    class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base flex-1">
                                <i class="fas fa-save mr-1 sm:mr-2"></i>
                                Actualizar Contraseña
                            </button>
                            <a href="{{ url()->previous() }}" 
                               class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base flex-1">
                                <i class="fas fa-times mr-1 sm:mr-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

.text-deep-teal-800 {
    color: rgba(1, 46, 64, 0.9);
}

.bg-deep-teal-50 {
    background-color: rgba(1, 46, 64, 0.05);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de contraseñas coincidentes
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    function validatePasswords() {
        if (password.value && passwordConfirmation.value) {
            if (password.value !== passwordConfirmation.value) {
                passwordConfirmation.setCustomValidity('Las contraseñas no coinciden');
            } else {
                passwordConfirmation.setCustomValidity('');
            }
        } else {
            passwordConfirmation.setCustomValidity('');
        }
    }
    
    password.addEventListener('input', validatePasswords);
    passwordConfirmation.addEventListener('input', validatePasswords);
});
</script>
@endsection