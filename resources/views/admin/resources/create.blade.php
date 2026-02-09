<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dar de Alta Nuevo Recurso') }}
        </h2>
    </x-slot>

    <!-- Inicializamos Alpine con 'type' vacío -->
    <div class="py-12" x-data="{ type: '{{ old('type') }}' }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('admin.resources.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- 1. SELECTOR DE TIPO -->
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 mb-6">
                            <label for="type" class="block mb-2 text-lg font-bold text-blue-900">¿Qué tipo de recurso vas a dar de alta?</label>
                            <select id="type" name="type" x-model="type" class="bg-white border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="" disabled selected>-- Selecciona una opción --</option>
                                <option value="equipo">Equipo (Cómputo, Audiovisual, Herramienta)</option>
                                <option value="laboratorio">Laboratorio (Espacio Físico)</option>
                                <option value="insumo">Insumo (Consumibles, Cables, Accesorios)</option>
                            </select>
                        </div>

                        <!-- CAMPOS DINÁMICOS -->
                        <div x-show="type !== ''" x-transition.opacity class="space-y-6">
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                
                                <!-- COLUMNA IZQUIERDA: Imagen -->
                                <div class="md:col-span-1">
                                    <label class="block mb-2 text-sm font-medium text-gray-900">Imagen del Recurso</label>
                                    
                                    <!-- Contenedor de la imagen: Altura fija y fondo gris para ajuste visual -->
                                    <div class="flex items-center justify-center w-full mb-4 bg-gray-100 rounded-lg border border-gray-300 overflow-hidden relative" style="height: 200px;">
                                        
                                        <!-- Imagen Previa: Se ajusta automáticamente con object-contain -->
                                        <img id="preview-image" class="hidden w-full h-full object-contain" alt="Vista previa">
                                        
                                        <!-- Placeholder (Instrucciones) -->
                                        <div id="placeholder-image" class="flex flex-col items-center justify-center p-4 text-center">
                                            <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <p class="text-xs text-gray-500 font-semibold">Subir imagen</p>
                                            <p class="text-[10px] text-gray-400 mt-1">Formato horizontal recomendado</p>
                                        </div>
                                    </div>

                                    <input type="file" name="image" id="image" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" onchange="previewImage(event)">
                                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                </div>

                                <!-- COLUMNA DERECHA: Datos del Formulario -->
                                <div class="md:col-span-2 space-y-4">
                                    
                                    <!-- Fila 1: No. Inventario y Carrera -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="inventory_number" class="block mb-2 text-sm font-medium text-gray-900">Número de Inventario UPB</label>
                                            <input type="text" name="inventory_number" id="inventory_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Ej. UPB-001" :value="'{{ old('inventory_number') }}'">
                                            <x-input-error :messages="$errors->get('inventory_number')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="career" class="block mb-2 text-sm font-medium text-gray-900">Asignar a Carrera</label>
                                            <select id="career" name="career" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                                <option value="" disabled selected>Selecciona...</option>
                                                <option value="ITID" {{ old('career') == 'ITID' ? 'selected' : '' }}>ITID (Tecnologías de la Inf.)</option>
                                                <option value="IAEV" {{ old('career') == 'IAEV' ? 'selected' : '' }}>IAEV (Animación y Efectos)</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('career')" class="mt-2" />
                                        </div>
                                    </div>

                                    <!-- Fila 2: Nombre -->
                                    <div>
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">
                                            <span x-show="type == 'equipo'">Nombre del Equipo</span>
                                            <span x-show="type == 'laboratorio'">Nombre del Laboratorio</span>
                                            <span x-show="type == 'insumo'">Nombre del Insumo</span>
                                            <span x-show="!type">Nombre del Recurso</span>
                                        </label>
                                        <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Ej. Laptop Dell / Lab de Redes" required :value="'{{ old('name') }}'">
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>

                                    <!-- Fila 3: Cantidad -->
                                    <div x-show="type !== 'laboratorio'">
                                        <label for="total_stock" class="block mb-2 text-sm font-medium text-gray-900">Cantidad</label>
                                        <input type="number" name="total_stock" id="total_stock" 
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                               value="1" min="1" 
                                               :required="type !== 'laboratorio'"> 
                                        <x-input-error :messages="$errors->get('total_stock')" class="mt-2" />
                                    </div>
                                    
                                    <!-- Input Oculto para Laboratorio -->
                                    <template x-if="type === 'laboratorio'">
                                        <input type="hidden" name="total_stock" value="1">
                                    </template>

                                    <!-- Fila 4: Descripción -->
                                    <div>
                                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900">
                                            <span x-show="type == 'equipo'">Detalles del Equipo</span>
                                            <span x-show="type == 'laboratorio'">Detalles del Laboratorio</span>
                                            <span x-show="type == 'insumo'">Detalles del Insumo</span>
                                        </label>
                                        <textarea id="description" name="description" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Especificaciones, ubicación, características...">{{ old('description') }}</textarea>
                                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                    </div>

                                    <input type="hidden" name="status" value="disponible">

                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                                <a href="{{ route('admin.resources.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5">
                                    Cancelar
                                </a>
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                                    Dar de Alta Recurso
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script JS para previsualizar imagen -->
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('preview-image');
                const placeholder = document.getElementById('placeholder-image');
                output.src = reader.result;
                output.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</x-app-layout>