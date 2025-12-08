<x-guest-layout>
    <div class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow-md sm:p-6 md:p-8">
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form class="space-y-6" method="POST" action="{{ route('login') }}">
            @csrf
            <h5 class="text-xl font-medium text-gray-900">Iniciar Sesión en SIPRELI</h5>

            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Tu email</label>
                <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="nombre@dominio.com" required :value="old('email')" autofocus>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Tu contraseña</label>
                <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
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