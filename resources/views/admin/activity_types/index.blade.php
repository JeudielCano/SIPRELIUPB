<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏷️ Gestión de Tipos de Actividad
            </h2>
            <a href="{{ route('admin.activity_types.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                + Nuevo Tipo
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
                    @if($activityTypes->isEmpty())
                        <div class="text-center py-12 text-gray-400">
                            No hay tipos de actividad registrados.
                        </div>
                    @else
                        <div class="relative overflow-x-auto border rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3">Nombre</th>
                                        <th class="px-6 py-3 text-center">Solicitudes</th>
                                        <th class="px-6 py-3 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activityTypes as $activityType)
                                        <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ $activityType->name }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                    {{ $activityType->loan_requests_count }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex justify-center gap-2">
                                                    <a href="{{ route('admin.activity_types.edit', $activityType) }}"
                                                       class="px-3 py-1.5 text-xs font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">
                                                        Editar
                                                    </a>
                                                    <form action="{{ route('admin.activity_types.destroy', $activityType) }}" method="POST"
                                                        onsubmit="return confirm('¿Eliminar este tipo de actividad?');">
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