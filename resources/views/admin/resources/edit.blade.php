<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Recurso') }}
        </h2>
    </x-slot>

    <!-- Inicializamos Alpine con el tipo actual del recurso para mostrar los campos correctos -->
    <div class="py-12" x-data="{ type: '{{ old('type', $resource->type) }}' }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- FORMULARIO PRINCIPAL (ACTUALIZAR) -->
                    <!-- Nota: Le damos un ID para vincular el botón de guardar más abajo -->
                    <form id="update-form" action="{{ route('admin.resources.update', $resource->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <!-- COLUMNA IZQUIERDA: Imagen -->
                            <div class="md:col-span-1">
                                <label class="block mb-2 text-sm font-medium text-gray-900">Imagen del Recurso</label>
                                
                                <!-- Contenedor de la imagen: Altura fija y fondo gris para ajuste visual -->
                                <div class="flex items-center justify-center w-full mb-4 bg-gray-100 rounded-lg border border-gray-300 overflow-hidden relative" style="height: 200px;">
                                    
                                    <!-- Imagen Actual o Nueva Previsualización -->
                                    <!-- Usamos object-contain para que se vea completa (horizontal o vertical) -->
                                    <img id="preview-image" 
                                         src="{{ $resource->image_path ? asset('storage/' . $resource->image_path) : '' }}" 
                                         class="{{ $resource->image_path ? '' : 'hidden' }} w-full h-full object-contain" 
                                         alt="Vista previa">
                                    
                                    <!-- Placeholder (Solo si no hay imagen) -->
                                    <div id="placeholder-image" class="{{ $resource->image_path ? 'hidden' : 'flex' }} flex-col items-center justify-center w-full h-full text-center p-4">
                                        <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="text-xs text-gray-500 font-semibold">Cambiar imagen</p>
                                        <p class="text-[10px] text-gray-400 mt-1">Formato horizontal recomendado</p>
                                    </div>
                                </div>

                                <input type="file" name="image" id="image" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" onchange="previewImage(event)">
                                <p class="mt-1 text-xs text-gray-500">Dejar vacío para mantener la actual.</p>
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>

                            <!-- COLUMNA DERECHA: Datos -->
                            <div class="md:col-span-2 space-y-4">
                                
                                <!-- Tipo (Select) -->
                                <div>
                                    <label for="type" class="block mb-2 text-sm font-medium text-gray-900">Tipo de Recurso</label>
                                    <select id="type" name="type" x-model="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        <option value="equipo" {{ old('type', $resource->type) == 'equipo' ? 'selected' : '' }}>Equipo</option>
                                        <option value="laboratorio" {{ old('type', $resource->type) == 'laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                                        <option value="insumo" {{ old('type', $resource->type) == 'insumo' ? 'selected' : '' }}>Insumo</option>
                                    </select>
                                </div>

                                <!-- Fila 1: No. Inventario y Carrera -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="inventory_number" class="block mb-2 text-sm font-medium text-gray-900">Número de Inventario</label>
                                        <input type="text" name="inventory_number" id="inventory_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('inventory_number', $resource->inventory_number) }}">
                                        <x-input-error :messages="$errors->get('inventory_number')" class="mt-2" />
                                    </div>
                                    <div>
                                        <label for="career" class="block mb-2 text-sm font-medium text-gray-900">Asignar a Carrera</label>
                                        <select id="career" name="career" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                            <option value="" disabled>Selecciona...</option>
                                            <option value="ITID" {{ old('career', $resource->career) == 'ITID' ? 'selected' : '' }}>ITID</option>
                                            <option value="IAEV" {{ old('career', $resource->career) == 'IAEV' ? 'selected' : '' }}>IAEV</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('career')" class="mt-2" />
                                    </div>
                                </div>

                                <!-- Nombre -->
                                <div>
                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nombre</label>
                                    <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('name', $resource->name) }}" required>
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Stock y Estado -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div x-show="type !== 'laboratorio'">
                                        <label for="total_stock" class="block mb-2 text-sm font-medium text-gray-900">Stock Total</label>
                                        <input type="number" name="total_stock" id="total_stock" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('total_stock', $resource->total_stock) }}" min="1">
                                        <x-input-error :messages="$errors->get('total_stock')" class="mt-2" />
                                    </div>
                                    
                                    <!-- Input Oculto para Laboratorio -->
                                    <template x-if="type === 'laboratorio'">
                                        <input type="hidden" name="total_stock" value="1">
                                    </template>

                                    <div>
                                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Estado</label>
                                        <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                            <option value="disponible" {{ old('status', $resource->status) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                            <option value="mantenimiento" {{ old('status', $resource->status) == 'mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                                            <option value="prestado" {{ old('status', $resource->status) == 'prestado' ? 'selected' : '' }}>Prestado</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Descripción -->
                                <div>
                                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Descripción</label>
                                    <textarea id="description" name="description" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $resource->description) }}</textarea>
                                </div>

                            </div>
                        </div>
                    </form>

                    <!-- BARRA DE ACCIONES (Separada del formulario principal) -->
                    <div class="flex flex-col-reverse md:flex-row justify-between items-center pt-6 mt-6 border-t border-gray-100 gap-4">
                        
                        <!-- BOTÓN DE ELIMINAR (DAR DE BAJA) -->
                        <form action="{{ route('admin.resources.destroy', $resource->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres dar de baja este recurso? Esta acción borrará el recurso y su historial de préstamos asociados de forma permanente.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-white border border-red-200 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">
                                Dar de baja
                            </button>
                        </form>

                        <!-- BOTONES DE GUARDAR/CANCELAR -->
                        <div class="flex space-x-4">
                            <a href="{{ route('admin.resources.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5">
                                Cancelar
                            </a>
                            <!-- El atributo form="update-form" vincula este botón al formulario principal de arriba -->
                            <button type="submit" form="update-form" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                                Actualizar Recurso
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Script para previsualizar imagen (Funciona igual) -->
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('preview-image');
                const placeholder = document.getElementById('placeholder-image');
                output.src = reader.result;
                output.classList.remove('hidden');
                placeholder.classList.add('hidden');
                placeholder.classList.remove('flex'); // Importante para quitar el flex del placeholder
            };
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</x-app-layout>