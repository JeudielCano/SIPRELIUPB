<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            {{-- Mensajes de éxito --}}
            @if(session('status') === 'profile-updated')
                <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
                    ✅ Perfil actualizado correctamente.
                </div>
            @endif
            @if(session('status') === 'password-updated')
                <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
                    ✅ Contraseña actualizada correctamente.
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 overflow-hidden">

                {{-- Avatar / Header de perfil --}}
                <div class="bg-gradient-to-r from-blue-700 to-blue-500 px-6 py-8 flex items-center gap-5">
                    <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-md">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold text-lg leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-blue-200 text-sm">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-1 text-xs bg-white/20 text-white px-2 py-0.5 rounded-full capitalize">
                            {{ Auth::user()->role }} · {{ Auth::user()->applicant_type ?? '---' }}
                        </span>
                    </div>
                </div>

                <div class="p-6 space-y-8">

                    {{-- SECCIÓN 1: Información Personal --}}
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Información Personal
                        </h3>

                        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                            @csrf
                            @method('patch')

                            {{-- Nombre --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', Auth::user()->name) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-400"
                                    required>
                                @error('name')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Correo --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', Auth::user()->email) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-400"
                                    required>
                                @error('email')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Teléfono --}}
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                <input type="text" id="phone_number" name="phone_number"
                                    value="{{ old('phone_number', Auth::user()->phone_number) }}"
                                    placeholder="Ej: 983 123 4567"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
                                @error('phone_number')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit"
                                    class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>

                    <hr class="border-gray-100">

                    {{-- SECCIÓN 2: Cambiar Contraseña --}}
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Cambiar Contraseña
                        </h3>

                        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                            @csrf
                            @method('put')

                            {{-- Contraseña actual --}}
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña actual</label>
                                <input type="password" id="current_password" name="current_password"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-400"
                                    autocomplete="current-password">
                                @error('current_password', 'updatePassword')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nueva contraseña --}}
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña</label>
                                <input type="password" id="password" name="password"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-400"
                                    autocomplete="new-password">
                                @error('password', 'updatePassword')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Confirmar contraseña --}}
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar nueva contraseña</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-400"
                                    autocomplete="new-password">
                                @error('password_confirmation', 'updatePassword')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit"
                                    class="px-6 py-2.5 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition-colors">
                                    Actualizar contraseña
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>