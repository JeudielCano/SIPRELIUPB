<x-guest-layout>
    <div class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow-md sm:p-6 md:p-8">
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form class="space-y-6" method="POST" action="{{ route('login') }}">
            @csrf
            <h5 class="text-xl font-medium text-gray-900">Iniciar Sesión en SIPRELI</h5>

            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Tu email</label>
                <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Ingresa tu correo" required :value="old('email')" autofocus>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div> <!--  Contraseña con boton para hacer visible     -->
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Tu contraseña</label>
                
                <div class="relative">
                    
                    <input type="password" name="password" id="password" placeholder="Ingresa tu contraseña" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10" required>
                    
                    <button type="button" 
                        onclick="
                            let input = document.getElementById('password');
                            let eyeOpen = document.getElementById('eye-open');
                            let eyeClosed = document.getElementById('eye-closed');
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
                        
                        <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>

                        <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-start">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="remember" type="checkbox" name="remember" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300">
                    </div>
                    <label for="remember" class="ms-2 text-sm font-medium text-gray-900">Recuérdame</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="ms-auto text-sm text-blue-700 hover:underline">¿Olvidaste tu contraseña?</a>
                @endif
            </div>

            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Iniciar Sesión</button>
            
            <div class="text-sm font-medium text-gray-500">
                ¿No estás registrado? <a href="{{ route('register') }}" class="text-blue-700 hover:underline">Crear cuenta</a>
            </div>
        </form>
    </div>
</x-guest-layout>