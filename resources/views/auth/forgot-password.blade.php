<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('¿Olvidaste tu contraseña? No hay problema. Simplemente indícanos tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña y podrás elegir una nueva.') }}
    </div>

    <div class="mb-4">
        {{-- Caso 1: El correo se envió con éxito --}}
        @if (session('status'))
            <div class="p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200" role="alert">
                <span class="font-medium">¡Correo valido!</span> Hemos enviado el enlace a tu correo.
            </div>
        @endif

        {{-- Caso 2: El correo no existe en la base de datos --}}
        {{-- Capturamos el error específico del campo email --}}
        @if ($errors->has('email'))
            <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
                <span class="font-medium">Error:</span> No encontramos ninguna cuenta asociada a este correo electrónico.
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700" />
            
            <x-text-input id="email" 
                class="block mt-1 w-full !bg-white !text-gray-900 border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus />
            
            {{-- Opcional: Puedes quitar este x-input-error si ya usas el cuadro de arriba --}}
            {{-- <x-input-error :messages="$errors->get('email')" class="mt-2" /> --}}
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="w-full justify-center bg-blue-700 hover:bg-blue-800">
                {{ __('ENVIAR ENLACE DE RESTABLECIMIENTO') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>