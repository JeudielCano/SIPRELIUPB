<x-guest-layout>
    <div class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow-md sm:p-6 md:p-8">
        <form class="space-y-6" method="POST" action="{{ route('register') }}" x-data="{ role: '{{ old('applicant_type') }}' }">
            @csrf
            <h5 class="text-xl font-medium text-gray-900">Crear una cuenta en SIPRELI</h5>

            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nombre Completo</label>
                <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Ej. Juan Pérez" required autofocus :value="old('name')">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Correo Electrónico</label>
                <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="nombre@ejemplo.com" required :value="old('email')">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-900">Teléfono</label>
                <input type="tel" name="phone_number" id="phone_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Ej. 9831234567" :value="old('phone_number')">
                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
            </div>

            <div>
                <label for="applicant_type" class="block mb-2 text-sm font-medium text-gray-900">Tipo de Solicitante</label>
                <select id="applicant_type" name="applicant_type" x-model="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    <option value="" disabled selected>Selecciona tu perfil</option>
                    <option value="alumno">Alumno</option>
                    <option value="docente">Docente</option>
                    <option value="externo">Externo</option>
                </select>
                <x-input-error :messages="$errors->get('applicant_type')" class="mt-2" />
            </div>

            <div x-show="role === 'alumno'" style="display: none;">
                <label for="student_id" class="block mb-2 text-sm font-medium text-gray-900">Matrícula</label>
                <input type="text" name="student_id" id="student_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Ej. 202300123" :value="old('student_id')">
                <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Registrarme</button>
            
            <div class="text-sm font-medium text-gray-500">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-blue-700 hover:underline">Inicia sesión aquí</a>
            </div>
        </form>
    </div>
</x-guest-layout>