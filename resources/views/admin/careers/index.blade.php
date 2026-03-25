<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🎓 Gestión de Carreras
            </h2>
            <a href="{{ route('admin.careers.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                + Nueva Carrera
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <x-auth-session-status :status="session('status')" />

            @if($errors->any())
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($careers->isEmpty())
                        <div class="text-center py-12 text-gray-400">
                            No hay carreras registradas.
                        </div>
                    @else
                        <div class="relative overflow-x-auto border rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3">Clave</th>
                                        <th class="px-6 py-3">Nombre Completo</th>
                                        <th class="px-6 py-3 text-center">Recursos</th>
                                        <th class="px-6 py-3 text-center">Estado</th>
                                        <th class="px-6 py-3 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($careers as $career)
                                        <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 font-bold text-gray-900">
                                                {{ $career->name }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $career->full_name ?? '---' }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                    {{ $career->resources_count }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if($career->active)
                                                    <span class="bg-green-100 text-green-700 text-xs font-medium px-2.5 py-0.5 rounded-full">Activa</span>
                                                @else
                                                    <span class="bg-gray-100 text-gray-500 text-xs font-medium px-2.5 py-0.5 rounded-full">Inactiva</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex justify-center gap-2">
                                                    <a href="{{ route('admin.careers.edit', $career) }}"
                                                       class="px-3 py-1.5 text-xs font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">
                                                        Editar
                                                    </a>
                                                    <form action="{{ route('admin.careers.destroy', $career) }}" method="POST"
                                                        onsubmit="return confirm('¿Eliminar esta carrera?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>