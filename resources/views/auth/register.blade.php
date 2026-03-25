<x-guest-layout>
    <div class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow-md sm:p-6 md:p-8">
        <form class="space-y-6" method="POST" action="{{ route('register') }}" x-data="{ role: '{{ old('applicant_type') }}' }">
            @csrf
            <h5 class="text-xl font-medium text-gray-900">Crear una cuenta en SIPRELI</h5>

            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nombre Completo</label>
                <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Escribe tu nombre" required autofocus :value="old('name')">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

<!-- Correo -->

            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Correo Electrónico</label>
                
                <div class="relative">
                    <div id="email-help" class="hidden absolute bottom-full left-0 mb-2 z-20 w-full sm:w-72">
                        <div class="bg-blue-600 text-white text-xs rounded-lg p-3 shadow-xl relative">
                            <span class="font-bold block mb-1">💡 Sugerencia:</span>
                            Debes agregar un correo existente para validación y recuperación de la cuenta. (se recomienda usar el institucional)
                            <div class="absolute h-2 w-2 bg-blue-600 rotate-45 -bottom-1 left-4"></div>
                        </div>
                    </div>

                    <input type="email" name="email" id="email" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 shadow-sm" 
                        placeholder="Ingresa un correo valido" 
                        required 
                        value="{{ old('email') }}"
                        onfocus="document.getElementById('email-help').classList.remove('hidden')"
                        onblur="document.getElementById('email-help').classList.add('hidden')">
                </div>

                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

<!-- Fin de correo -->
            <div>
                <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-900">Teléfono</label>
                <input type="tel" name="phone_number" id="phone_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Digita tu numero" :value="old('phone_number')">
                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
            </div>

            <div>
                <label for="applicant_type" class="block mb-2 text-sm font-medium text-gray-900">Tipo de Solicitante</label>
                <select id="applicant_type" name="applicant_type" x-model="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    <option value="" disabled selected>Soy un :</option>
                    <option value="alumno">Alumno</option>
                    <option value="docente">Docente</option>
                    <option value="externo">Externo (persona ajena a la intitución)</option>
                </select>
                <x-input-error :messages="$errors->get('applicant_type')" class="mt-2" />
            </div>

            <div x-show="role === 'alumno'" style="display: none;">
                <label for="student_id" class="block mb-2 text-sm font-medium text-gray-900">Matrícula</label>
                <input type="text" name="student_id" id="student_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Digita tu Matricula UPB" :value="old('student_id')">
                <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
            </div>

<!--    Contraseñas     -->

            <div> <!-- Crear -->
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Contraseña</label>
                <div class="relative">
                    <input type="password" name="password" id="password" placeholder="Crea tu contraseña" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10" required autocomplete="new-password">
                    
                    <button type="button" 
                        onclick="
                            let input = document.getElementById('password');
                            let eyeOpen = document.getElementById('eye-open-pwd');
                            let eyeClosed = document.getElementById('eye-closed-pwd');
                            if(input.type === 'password') {
                                input.type = 'text';
                                eyeOpen.classList.add('hidden');
                                eyeClosed.classList.remove('hidden');
                            } else {
                                input.type = 'password';
                                eyeClosed.classList.add('hidden');
                                eyeOpen.classList.remove('hidden');
                            }
                        "
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-blue-600 focus:outline-none transition-colors">
                        
                        <svg id="eye-open-pwd" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>

                        <svg id="eye-closed-pwd" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div> <!-- Confirmar -->
                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirmar Contraseña</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirma tu contraseña" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10" required autocomplete="new-password">
                    
                    <button type="button" 
                        onclick="
                            let input = document.getElementById('password_confirmation');
                            let eyeOpen = document.getElementById('eye-open-conf');
                            let eyeClosed = document.getElementById('eye-closed-conf');
                            if(input.type === 'password') {
                                input.type = 'text';
                                eyeOpen.classList.add('hidden');
                                eyeClosed.classList.remove('hidden');
                            } else {
                                input.type = 'password';
                                eyeClosed.classList.add('hidden');
                                eyeOpen.classList.remove('hidden');
                            }
                        "
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-blue-600 focus:outline-none transition-colors">
                        
                        <svg id="eye-open-conf" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>

                        <svg id="eye-closed-conf" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
<!--    Fin contraseñas     -->

            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Crear cuenta</button>
            
            <div class="text-sm font-medium text-gray-500">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-blue-700 hover:underline">Inicia sesión aquí</a>
            </div>
        </form>
    </div>
</x-guest-layout>