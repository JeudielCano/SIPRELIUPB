<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🎓 Editar Carrera: <span class="text-blue-600">{{ $career->name }}</span>
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

                    <form method="POST" action="{{ route('admin.careers.update', $career) }}">
                        @csrf @method('PATCH')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Clave <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $career->name) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-300 uppercase"
                                maxlength="50" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre Completo
                            </label>
                            <input type="text" name="full_name" value="{{ old('full_name', $career->full_name) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-300"
                                maxlength="255">
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="active" value="1"
                                    {{ $career->active ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 rounded">
                                <span class="text-sm font-medium text-gray-700">Carrera activa</span>
                            </label>
                        </div>

                        <div class="flex justify-between items-center">
                            <a href="{{ route('admin.careers.index') }}"
                               class="text-sm text-gray-500 hover:text-gray-700">← Volver</a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>