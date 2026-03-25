<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🎓 Nueva Carrera
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if($errors->any())
                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.careers.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Clave <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-300 uppercase"
                                placeholder="Ej: LITIID" maxlength="50" required>
                            <p class="text-xs text-gray-400 mt-1">Se guardará en mayúsculas automáticamente.</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre Completo
                            </label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-300"
                                placeholder="Ej: Licenciatura en Tecnologías de la Información e Innovación Digital" maxlength="255">
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="active" value="1" checked
                                    class="w-4 h-4 text-blue-600 rounded">
                                <span class="text-sm font-medium text-gray-700">Carrera activa</span>
                            </label>
                        </div>

                        <div class="flex justify-between items-center">
                            <a href="{{ route('admin.careers.index') }}"
                               class="text-sm text-gray-500 hover:text-gray-700">← Volver</a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                                Guardar Carrera
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>