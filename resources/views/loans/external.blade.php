<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nueva Solicitud de Préstamo') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="loanForm()" x-cloak>
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mensajes de Error de Laravel -->
            @if ($errors->any())
                <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
                    <div class="font-bold mb-2">Por favor corrige los siguientes errores:</div>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('loans.store') }}" method="POST" @submit.prevent="submitForm">
                @csrf

                <!-- SECCIÓN 1: SELECCIÓN DE RECURSOS -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-8">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold me-2 px-2.5 py-0.5 rounded border border-blue-400">1</span>
                            Selección de Recursos
                        </h3>
                    </div>
                    
                    <div class="p-6 bg-gray-50">

                        <div class="mb-6">
                            <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto flex items-center justify-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-100 border border-blue-200 rounded-lg hover:bg-blue-200 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                Ver catálogo completo de recursos disponibles
                            </a>
                        </div>

                        <!-- Fila 1: Filtro de Tipo y Cantidad -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                            <!-- Select de Tipo de Recurso -->
                            <div class="md:col-span-4">
                                <label class="block mb-2 text-sm font-medium text-gray-900">Tipo de Recurso</label>
                                <select x-model="resourceTypeFilter" @change="resetSearch()" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                                    <option value="" disabled selected>-- Selecciona --</option>
                                    <option value="equipo">Equipo</option>
                                    @if(Auth::user()->applicant_type !== 'alumno')
                                        <option value="laboratorio">Laboratorio</option>
                                    @endif
                                    <option value="insumo">Insumo</option>
                                </select>
                            </div>

                            <div class="md:col-span-8 relative">
                                <label class="block mb-2 text-sm font-medium text-gray-900">Buscar Recurso</label>
                                <input type="text" x-model="searchQuery" @input="showDropdown = true" @click.away="showDropdown = false" :disabled="resourceTypeFilter === ''" placeholder="Escribe para buscar..." class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 disabled:bg-gray-100">
                                
                                <div x-show="showDropdown && searchQuery.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                                    <template x-for="resource in filteredResources" :key="resource.id">
                                        <div @click="selectResource(resource)" 
                                            class="px-4 py-3 cursor-pointer border-b border-gray-100 flex justify-between items-center group transition-colors duration-150"
                                            :class="resource.total_stock > 0 ? 'hover:bg-blue-50' : 'bg-red-50 hover:bg-red-100'">
                                            
                                            <div>
                                                <div class="font-bold text-gray-800" x-text="resource.name"></div>
                                                <div class="text-xs text-gray-500">
                                                    <span x-text="resource.inventory_number || 'S/N'"></span> | 
                                                    
                                                    <span class="font-bold" :class="resource.total_stock > 0 ? 'text-green-600' : 'text-red-600'">
                                                        Disp: <span x-text="resource.total_stock"></span>
                                                    </span>
                                                </div>
                                            </div>

                                            <span class="text-xs px-2 py-1 rounded" 
                                                :class="resource.total_stock > 0 ? 'bg-blue-100 text-blue-800' : 'bg-red-200 text-red-800 font-bold'">
                                                <span x-text="resource.total_stock > 0 ? 'Seleccionar' : 'Agotado'"></span>
                                            </span>
                                        </div>
                                    </template>
                                    
                                    <div x-show="filteredResources.length === 0" class="px-4 py-3 text-sm text-gray-500 italic">
                                        No se encontraron recursos.
                                    </div>
                                </div>
                            </div>
                            <!-- Input de Cantidad -->
                            <div class="md:col-span-12">
                                <label class="block mb-2 text-sm font-medium text-gray-900">Cantidad</label>
                                <input type="number" x-model.number="quantity" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" min="1">
                            </div>


                        </div>
                                                    <!-- boton agregar -->
                            <div x-show="selectedResourceName" class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg flex justify-between items-center" style="display: none;">
                                <div>
                                    <span class="text-xs text-blue-600 uppercase font-bold">Listo para agregar:</span>
                                    <div class="font-bold text-gray-900" x-text="selectedResourceName"></div>
                                </div>
                                <button type="button" @click="addItem()" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 transition-transform active:scale-95">
                                    Agregar a la Lista ⬇
                                </button>
                            </div>



                        <!-- TABLA DE LA LISTA (CARRITO) -->
                        <div x-show="items.length > 0" class="relative overflow-x-auto shadow-sm sm:rounded-lg border border-gray-200 bg-white">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3">Recurso</th>
                                        <th class="px-6 py-3 text-center">Cantidad</th>
                                        <th class="px-6 py-3 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-6 py-4 font-medium text-gray-900" x-text="item.name"></td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-500" x-text="item.quantity"></span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <button type="button" @click="removeItem(index)" class="font-medium text-red-600 hover:underline">Quitar</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div x-show="items.length === 0" class="text-center py-4 text-gray-400 text-sm border-2 border-dashed border-gray-300 rounded-lg">
                            Tu lista de préstamo está vacía. Selecciona recursos arriba.
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 2: DETALLES (Mismo Día) -->

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-8">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold me-2 px-2.5 py-0.5 rounded border border-blue-400">2</span>
                            Detalles del Préstamo
                        </h3>
                    </div>

                    <div class="p-6">
                        <!-- Fechas y Validación -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                            <h4 class="text-sm font-bold text-gray-700 mb-3 uppercase flex items-center">
                                Periodo del Préstamo Externo (Maximo 4 dias)
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                                <!-- Fecha y Hora de Retiro -->
                                <div>
                                    <label for="pickup_at" class="block mb-2 text-sm font-medium text-gray-900">Fecha y Hora de Retiro</label>
                                    <input type="datetime-local" 
                                           name="pickup_at" 
                                           id="pickup_at" 
                                           x-model="pickupDate" 
                                           @change="updateDueDate"
                                           class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <p class="mt-1 text-xs text-gray-500">Selecciona cuándo recogerás el material.</p>
                                </div>

                                <!-- Solo Hora de Devolución -->
                                <div>
                                    <label for="return_time" class="block mb-2 text-sm font-medium text-gray-900">Fecha de devolución</label>
                                    <div class="relative">
                                        <input type="datetime-local"
                                               name="due_at" 
                                               id="due_at" 
                                               x-model="dueDate" 
                                               @change="validateDates()" 
                                               class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">

                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Debe devolverse el día seleccionado.</p>
                                </div>
                            </div>
                            
                            <!-- Input Oculto para due_at (Calculado) -->
                            <input type="hidden" name="due_at" x-model="dueDate">

                            <!-- Botón de Confirmación de Fechas -->
                            <div class="mt-4 flex items-center justify-between bg-white p-3 rounded border border-gray-200">
                                <div class="flex items-center">
                                    <button type="button" 
                                            @click="validateDates()"
                                            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 me-2"
                                            :class="{'bg-green-100 text-green-700 border-green-400': datesValid, 'bg-white text-gray-900': !datesValid}">
                                        <span x-show="!datesValid">Validar Horario</span>
                                        <span x-show="datesValid" style="display: none;">✓ Horario Correcto</span>
                                    </button>
                                    <span x-show="dateError" class="text-red-600 text-sm font-medium ml-2" x-text="dateError"></span>
                                </div>
                                <div x-show="datesValid" style="display: none;" class="text-xs text-gray-500 text-right">
                                    Entrega programada para: <br>
                                    <strong x-text="new Date(dueDate).toLocaleString()"></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Actividad y Asignatura -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="activity_type_id" class="block mb-2 text-sm font-medium text-gray-900">Tipo de Actividad</label>
                                <select name="activity_type_id" id="activity_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="" disabled selected>Selecciona...</option>
                                    @foreach($activityTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="subject_id" class="block mb-2 text-sm font-medium text-gray-900">Asignatura</label>
                                <select name="subject_id" id="subject_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="" disabled selected>Selecciona...</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Documento obligatorio -->
                        <div class="mb-6 flex items-center justify-between bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div>
                                <h5 class="font-bold text-gray-800">Permiso Obligatorio</h5>
                                <p class="text-xs text-gray-500">Debes descargar, firmar y presentar este documento.</p>
                            </div>
                            <a href="{{ route('loans.external.permit') }}" target="_blank" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Descargar Permiso PDF
                            </a>
                        </div>

                        <!-- Observaciones -->
                        <div class="mb-6">
                            <label for="observations" class="block mb-2 text-sm font-medium text-gray-900">Observaciones</label>
                            <textarea id="observations" name="observations" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Escribe aquí cualquier detalle adicional..."></textarea>
                        </div>

                        <!-- Input Oculto JSON -->
                        <input type="hidden" name="selected_items" :value="JSON.stringify(items)">

                        <!-- Botón Final (Alineado a la derecha) -->
                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <button type="submit" 
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-8 py-3 focus:outline-none shadow-lg transition-transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:transform-none"
                                    :disabled="items.length === 0 || !datesValid">
                                Confirmar y Enviar Solicitud
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>





<!--
    <script>
        function loanForm() {
            return {
                allResources: @json($resources),
                resourceTypeFilter: '', searchQuery: '', showDropdown: false,
                selectedResourceId: '', selectedResourceName: '', selectedResourceStock: 0, quantity: 1,
                items: [],
                
                pickupDate: '',
                dueDate: '',
                datesValid: false,
                dateError: '',

                // ... (mismos métodos de filtro y selección que el anterior) ...
                get filteredResources() {
                    if (this.searchQuery === '') return [];
                    return this.allResources.filter(r => {
                        const typeMatch = this.resourceTypeFilter === '' || r.type === this.resourceTypeFilter;
                        const textMatch = r.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                        return typeMatch && textMatch;
                    });
                },
                selectResource(r) { this.selectedResourceId = r.id; this.selectedResourceName = r.name; this.selectedResourceStock = r.total_stock; this.searchQuery = ''; this.showDropdown = false; },
                addItem() {
                    if (!this.selectedResourceId) return;
                    
                    // Validar stock
                    if (this.quantity > this.selectedResourceStock) {
                        alert(`Solo hay ${this.selectedResourceStock} unidades disponibles.`);
                        return;
                    }
                    // Validar duplicados
                    if (this.items.some(i => i.id == this.selectedResourceId)) {
                        alert('Este recurso ya está en tu lista.');
                        return;
                    }
                    
                    this.items.push({
                        id: this.selectedResourceId,
                        name: this.selectedResourceName,
                        quantity: this.quantity
                    });
                    this.resetSelection();
                },
                removeItem(i) { this.items.splice(i, 1); },
                resetSearch() { this.searchQuery = ''; },

                // LÓGICA DE FECHAS ACTUALIZADA (MAX 4 DÍAS)
                validateDates() {
                    this.dateError = '';
                    this.datesValid = false;

                    if (!this.pickupDate || !this.dueDate) return;

                    const pickup = new Date(this.pickupDate);
                    const due = new Date(this.dueDate);
                    const now = new Date();

                    if (pickup < now) {
                        this.dateError = 'La fecha de retiro debe ser futura.';
                        return;
                    }

                    if (due <= pickup) {
                        this.dateError = 'La fecha de devolución debe ser posterior al retiro.';
                        return;
                    }

                    // Diferencia en milisegundos -> días
                    const diffTime = Math.abs(due - pickup);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 

                    if (diffDays > 4) {
                        this.dateError = 'El préstamo externo no puede durar más de 4 días.';
                        return;
                    }

                    this.datesValid = true;
                },
                
                resetSelection() {
                    this.selectedResourceId = '';
                    this.selectedResourceName = '';
                    this.selectedResourceStock = 0;
                    this.quantity = 1;
                },

                submitForm(e) {
                    this.validateDates();
                    if (this.items.length > 0 && this.datesValid) {
                        e.target.submit();
                    }
                }
            }
        }
    </script>

-->
    <!-- Script Alpine.js -->
    <script>
        // PASO CRUCIAL: Inyectamos los datos en una variable global antes de la función.
        // Esto separa el PHP de la estructura del objeto JS y evita errores de sintaxis.
        const dbResources = @json($resources);

        function loanForm() {
            return {
                allResources: dbResources,
                
                resourceTypeFilter: '',
                searchQuery: '',
                showDropdown: false,
                
                selectedResourceId: '',
                selectedResourceName: '',
                selectedResourceStock: 0,
                quantity: 1,
                
                items: [],
                
                // Date Validation Variables
                pickupDate: '',
                dueDate: '', // Variable unificada con x-model="dueDate" del HTML
                datesValid: false,
                dateError: '',

                get filteredResources() {
                    if (this.searchQuery === '') return [];
                    return this.allResources.filter(resource => {
                        const matchType = this.resourceTypeFilter === '' || resource.type === this.resourceTypeFilter;
                        const searchLower = this.searchQuery.toLowerCase();
                        const matchText = resource.name.toLowerCase().includes(searchLower) || 
                                          (resource.inventory_number && resource.inventory_number.toLowerCase().includes(searchLower));
                        
                        return matchType && matchText;
                    });
                },

                // MÉTODO: Sincronizar estado de validación
                updateDueDate() {
                    this.datesValid = false;
                    // Al usar datetime-local en ambos inputs, no es necesario concatenar.
                    // Solo reseteamos el error si el usuario cambia los campos.
                    this.dateError = '';
                },

                validateDates() {
                    this.dateError = '';
                    this.datesValid = false;

                    // Validación de campos vacíos usando dueDate
                    if (!this.pickupDate || !this.dueDate) {
                        this.dateError = 'Completa el horario.';
                        return;
                    }

                    const pickup = new Date(this.pickupDate);
                    const due = new Date(this.dueDate);
                    const now = new Date();

                    if (pickup < now) {
                        this.dateError = 'La fecha de retiro debe ser futura.';
                        return;
                    }

                    if (due <= pickup) {
                        this.dateError = 'La fecha de devolución debe ser posterior a la de retiro.';
                        return;
                    }

                    // Diferencia en milisegundos -> días
                    const diffTime = Math.abs(due - pickup);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 

                    if (diffDays > 4) {
                        this.dateError = 'El préstamo externo no puede durar más de 4 días.';
                        return;
                    }

                    this.datesValid = true;
                },

                selectResource(resource) {
                    // --- VALIDACIÓN VISUAL DE STOCK ---
                    if (resource.total_stock <= 0) {
                        alert('Lo sentimos, este recurso está actualmente agotado o en uso.');
                        return;
                    }

                    this.selectedResourceId = resource.id;
                    this.selectedResourceName = resource.name;
                    this.selectedResourceStock = resource.total_stock;
                    this.searchQuery = '';
                    this.showDropdown = false;
                },

                addItem() {
                    if (!this.selectedResourceId) return;
                    
                    // Validar stock
                    if (this.quantity > this.selectedResourceStock) {
                        alert(`Solo hay ${this.selectedResourceStock} unidades disponibles.`);
                        return;
                    }
                    // Validar duplicados
                    if (this.items.some(i => i.id == this.selectedResourceId)) {
                        alert('Este recurso ya está en tu lista.');
                        return;
                    }
                    
                    this.items.push({
                        id: this.selectedResourceId,
                        name: this.selectedResourceName,
                        quantity: this.quantity
                    });
                    this.resetSelection();
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                },

                resetSearch() {
                    this.searchQuery = '';
                    this.resetSelection();
                },

                resetSelection() {
                    this.selectedResourceId = '';
                    this.selectedResourceName = '';
                    this.selectedResourceStock = 0;
                    this.quantity = 1;
                },

                submitForm(e) {
                    if (this.items.length === 0) {
                        alert('Agrega al menos un recurso.');
                        return;
                    }
                    
                    this.validateDates();
                    if (!this.datesValid) {
                        alert('Verifica las fechas.');
                        return;
                    }
                    
                    e.target.submit();
                }
            }
        }
    </script>

</x-app-layout>