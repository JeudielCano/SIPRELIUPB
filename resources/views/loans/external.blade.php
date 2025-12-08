<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Solicitud de Préstamo Externo') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="loanForm()" x-cloak>
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alerta Informativa -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 shadow-sm">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Nota Importante:</strong> Los préstamos externos tienen una duración máxima de <strong>4 días</strong>. Debes descargar, firmar y entregar el permiso correspondiente.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Errores -->
            @if ($errors->any())
                <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('loans.external.store') }}" method="POST" @submit.prevent="submitForm">
                @csrf

                <!-- SECCIÓN 1: SELECCIÓN DE RECURSOS (Igual que el normal) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-8">
                    <div class="p-6 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900">1. Selección de Recursos</h3>
                    </div>
                    <div class="p-6">
                        <!-- ... (Aquí va la misma lógica de selección que el create.blade.php anterior) ... -->
                        <!-- Para ahorrar espacio, usa la misma estructura de tu create.blade.php para el buscador -->
                         <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                            <div class="md:col-span-8">
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
                            <div class="md:col-span-4">
                                <label class="block mb-2 text-sm font-medium text-gray-900">Cantidad</label>
                                <input type="number" x-model.number="quantity" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" min="1">
                            </div>
                        </div>

                        <div class="mb-6 relative">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Buscar Recurso</label>
                            <input type="text" x-model="searchQuery" @input="showDropdown = true" @click.away="showDropdown = false" :disabled="resourceTypeFilter === ''" placeholder="Escribe para buscar..." class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 disabled:bg-gray-100">
                            
                            <!-- Dropdown Resultados -->
                            <div x-show="showDropdown && searchQuery.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                                <template x-for="resource in filteredResources" :key="resource.id">
                                    <div @click="selectResource(resource)" class="px-4 py-3 cursor-pointer hover:bg-blue-50 border-b border-gray-100">
                                        <div class="font-bold" x-text="resource.name"></div>
                                        <div class="text-xs text-gray-500">Stock: <span x-text="resource.total_stock"></span></div>
                                    </div>
                                </template>
                            </div>
                            
                            <!-- Selección -->
                            <div x-show="selectedResourceName" class="mt-2 p-3 bg-blue-50 border-blue-200 rounded-lg flex justify-between items-center" style="display: none;">
                                <span x-text="selectedResourceName" class="font-bold"></span>
                                <button type="button" @click="addItem()" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-4 py-2">Agregar</button>
                            </div>
                        </div>

                        <!-- Tabla Items -->
                        <div x-show="items.length > 0" class="relative overflow-x-auto border rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                    <tr><th class="px-6 py-3">Recurso</th><th class="px-6 py-3">Cant.</th><th class="px-6 py-3"></th></tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr class="bg-white border-b">
                                            <td class="px-6 py-4 font-medium text-gray-900" x-text="item.name"></td>
                                            <td class="px-6 py-4" x-text="item.quantity"></td>
                                            <td class="px-6 py-4 text-right"><button type="button" @click="removeItem(index)" class="text-red-600 hover:underline">Quitar</button></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 2: DETALLES (Modificada para Préstamo Externo) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-8">
                    <div class="p-6 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900">2. Detalles y Permiso</h3>
                    </div>

                    <div class="p-6">
                        <!-- Fechas (Selector completo, no solo hora) -->
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-6">
                            <h4 class="text-sm font-bold text-blue-800 mb-3 uppercase">Periodo del Préstamo Externo</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="pickup_at" class="block mb-2 text-sm font-medium text-gray-900">Fecha de Retiro</label>
                                    <input type="datetime-local" name="pickup_at" id="pickup_at" x-model="pickupDate" @change="validateDates()" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                                </div>
                                <div>
                                    <label for="due_at" class="block mb-2 text-sm font-medium text-gray-900">Fecha de Devolución (Max 4 días)</label>
                                    <input type="datetime-local" name="due_at" id="due_at" x-model="dueDate" @change="validateDates()" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-red-600 font-medium" x-text="dateError" x-show="dateError"></p>
                        </div>

                        <!-- Actividad y Asignatura -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">Actividad</label>
                                <select name="activity_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                                    <option value="" disabled selected>Selecciona...</option>
                                    @foreach($activityTypes as $type) <option value="{{ $type->id }}">{{ $type->name }}</option> @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">Asignatura</label>
                                <select name="subject_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                                    <option value="" disabled selected>Selecciona...</option>
                                    @foreach($subjects as $subject) <option value="{{ $subject->id }}">{{ $subject->name }}</option> @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Botón de Descarga de Permiso -->
                        <div class="mb-6 flex items-center justify-between bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div>
                                <h5 class="font-bold text-gray-800">Permiso Obligatorio</h5>
                                <p class="text-xs text-gray-500">Debes descargar, firmar y presentar este documento.</p>
                            </div>
                            <a href="{{ route('loans.external.permit') }}" target="_blank" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Descargar Permiso PDF
                            </a>
                        </div>

                        <!-- Observaciones -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Observaciones</label>
                            <textarea name="observations" rows="2" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300" placeholder="Motivo del préstamo externo..."></textarea>
                        </div>

                        <input type="hidden" name="selected_items" :value="JSON.stringify(items)">

                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <button type="submit" :disabled="items.length === 0 || !datesValid" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-8 py-3 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                Enviar Solicitud Externa
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
                    if(!this.selectedResourceId) return;
                    this.items.push({id: this.selectedResourceId, name: this.selectedResourceName, quantity: this.quantity});
                    this.selectedResourceId = ''; this.quantity = 1; 
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

                submitForm(e) {
                    this.validateDates();
                    if (this.items.length > 0 && this.datesValid) {
                        e.target.submit();
                    }
                }
            }
        }
    </script>
</x-app-layout>