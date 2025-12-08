<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reglamentos y Normatividad') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mensaje Informativo -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 shadow-sm rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Es responsabilidad de todos los usuarios conocer y acatar los reglamentos vigentes para el uso de las instalaciones y equipos de la UPB.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Grid de Documentos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($regulations as $doc)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-200">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <!-- Icono PDF -->
                                <div class="p-3 bg-red-100 rounded-lg text-red-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">PDF</span>
                            </div>
                            
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $doc['title'] }}</h3>
                            <p class="text-sm text-gray-600 mb-4 h-10 line-clamp-2">
                                {{ $doc['description'] }}
                            </p>
                            
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                <span class="text-xs text-gray-400">Actualizado: {{ \Carbon\Carbon::parse($doc['date'])->format('d M, Y') }}</span>
                                
                                <!-- Botón Descargar (Simulado) -->
                                <a href="{{ route('regulations.download', ['filename' => $doc['file']]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center transition-colors">
                                    Descargar
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>