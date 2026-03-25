<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🛡️ Asignar Recursos a <span class="text-blue-600">{{ $user->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-200 overflow-hidden">
                <div class="p-6">

                    <p class="text-sm text-gray-500 mb-6">
                        Selecciona los recursos para este docente. Usa el buscador para navegar entre el inventario.
                    </p>

                    <form action="{{ url()->current() }}" method="GET" class="mb-6 flex gap-2">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Buscar recurso por nombre..."
                                   class="pl-10 w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm font-bold rounded-lg hover:bg-gray-900 transition-all">
                            Filtrar
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.guardians.update', $user) }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            @forelse($resources as $resource)
                                @php $isSelected = in_array($resource->id, $assignedIds); @endphp
                                <label class="flex items-center p-4 rounded-xl border-2 transition-all cursor-pointer group
                                    {{ $isSelected ? 'border-blue-500 bg-blue-50/50' : 'border-gray-100 hover:border-blue-200 hover:bg-gray-50' }}">
                                    
                                    <input type="checkbox" name="resources[]" value="{{ $resource->id }}"
                                           {{ $isSelected ? 'checked' : '' }}
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 transition-all">
                                    
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-gray-900 group-hover:text-blue-700 transition-colors">{{ $resource->name }}</p>
                                        <p class="text-[11px] text-gray-500 uppercase tracking-tight">{{ $resource->type }} · Stock: {{ $resource->total_stock }}</p>
                                    </div>
                                </label>
                            @empty
                                <div class="col-span-2 py-8 text-center text-gray-400 italic">
                                    No se encontraron recursos con ese nombre.
                                </div>
                            @endforelse
                        </div>

                        <div class="mb-8">
                            {{ $resources->links() }}
                        </div>

                        <div class="flex justify-between items-center pt-6 border-t border-gray-100">
                            <a href="{{ route('admin.guardians.index') }}"
                               class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                Volver al listado
                            </a>
                            <button type="submit"
                                    class="px-8 py-2.5 bg-blue-600 text-white text-sm font-black rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 active:scale-95 transition-all">
                                GUARDAR ASIGNACIÓN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>