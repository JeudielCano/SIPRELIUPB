<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\LoanItem;
use App\Models\ResourceGuardian;
// Para generar los pdf
use Barryvdh\DomPDF\Facade\Pdf;

class ResourceController extends Controller
{
    /**
     * Muestra la lista de recursos (Inventario) con filtros.
     */
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        $query = Resource::where('status', '!=', 'dado_de_baja');

        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }
        // ← AGREGA ESTO:
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('inventory_number', 'LIKE', '%' . $request->search . '%');
            });
        }

        $resources = $query->paginate(10)->withQueryString();
        $disabledResources = Resource::where('status', 'dado_de_baja')->get();

        return view('admin.resources.index', compact('resources', 'disabledResources'));
    }
            

    /**
     * Muestra el formulario para dar de alta un nuevo recurso.
     */
    public function create()
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        // 1. Extraemos las carreras de la base de datos
        $careers = \App\Models\Career::where('active', true)->orderBy('name')->get();

        // 2. Se las enviamos a la vista usando compact()
        return view('admin.resources.create', compact('careers'));
    }

    /**
     * Guarda el nuevo recurso en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['equipo', 'laboratorio', 'insumo'])],
            'career_id' => ['required', 'exists:careers,id'], // Validación de carrera
            'inventory_number' => ['nullable', 'string', 'max:255', 'unique:resources,inventory_number'],
            'total_stock' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['disponible', 'prestado', 'mantenimiento'])],
            'image' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ]);

        // Procesar Subida de Imagen
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('resources', 'public');
            $validatedData['image_path'] = $path;
        }

        Resource::create($validatedData);

        return redirect()->route('admin.resources.index')->with('status', '¡Recurso dado de alta correctamente!');
    }

    /**
     * Muestra el formulario para editar un recurso existente.
     */
    public function edit(Resource $resource)
    {
        if (auth()->user()->role !== 'administrador') abort(403);
        
        $careers = \App\Models\Career::where('active', true)->orderBy('name')->get(); // ← agrega esto
        
        return view('admin.resources.edit', compact('resource', 'careers'));
    }
    /**
     * Actualiza la información del recurso.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        $resource = Resource::findOrFail($id);

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['equipo', 'laboratorio', 'insumo'])],
            'career' => ['required', 'exists:careers,id'],
            'inventory_number' => ['nullable', 'string', 'max:255', Rule::unique('resources', 'inventory_number')->ignore($resource->id)],
            'total_stock' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['disponible', 'prestado', 'mantenimiento', 'dado_de_baja'])],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        // Procesar Nueva Imagen (y borrar la vieja para no llenar el servidor)
        if ($request->hasFile('image')) {
            // Borrar imagen anterior si existe
            if ($resource->image_path) {
                Storage::disk('public')->delete($resource->image_path);
            }
            
            $path = $request->file('image')->store('resources', 'public');
            $validatedData['image_path'] = $path;
        }

        $resource->update($validatedData);

        return redirect()->route('admin.resources.index')->with('status', '¡Recurso actualizado correctamente!');
    }

    // Dar de baja (cambia estado)
    public function disable(Resource $resource)
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        $resource->update(['status' => 'dado_de_baja']);

        return back()->with('status', "'{$resource->name}' ha sido dado de baja.");
    }

    // Recuperar recurso dado de baja
    public function recover(Resource $resource)
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        $resource->update(['status' => 'disponible']);

        return back()->with('status', "'{$resource->name}' ha sido recuperado.");
    }


    /**
     */
    // Eliminar permanentemente (solo recursos dados de baja)
    public function destroy(Resource $resource)
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        if ($resource->status !== 'dado_de_baja') {
            return back()->withErrors(['status' => 'Solo se pueden eliminar recursos que estén dados de baja.']);
        }

        // Eliminar loan_items asociados
        LoanItem::where('resource_id', $resource->id)->delete();

        // Eliminar asignaciones de subresguardantes
        ResourceGuardian::where('resource_id', $resource->id)->delete();

        // Eliminar imagen
        if ($resource->image_path) {
            Storage::disk('public')->delete($resource->image_path);
        }

        $resource->delete();

        return back()->with('status', "Recurso eliminado permanentemente.");
    }

    // Para poder generar pdf de descargas
    public function downloadBajas()
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        // 1. OBTENER Y CONVERTIR EL LOGO A BASE64
        $logoPath = public_path('images/logo2-upb.png'); // Ruta física en el servidor
        $logoBase64 = '';
        // Verificamos que el archivo exista para evitar errores
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            // Creamos la cadena Base64 lista para el HTML
            $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }

        // Obtenemos los recursos dados de baja con su carrera asignada
        $resources = Resource::where('status', 'dado_de_baja')
                            ->with('assignedCareer') 
                            ->orderBy('name')
                            ->get();

        // Cargamos la vista creada
        $pdf = Pdf::loadView('admin.resources.pdf-bajas', compact('resources', 'logoBase64'));
        // Retornamos el PDF para descarga con la fecha actual
        return $pdf->download('reporte-bajas-'.now()->format('d-m-Y').'.pdf');
    }

    // descargar inventario
    public function downloadInventory()
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        // 1. Preparar el Logo (Base64)
        $logoPath = public_path('images/logo2-upb.png');
        $logoBase64 = file_exists($logoPath) 
            ? 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath)) 
            : null;

        // 2. Obtener todos los recursos activos
        $resources = Resource::where('status', '!=', 'dado_de_baja')
                            ->with('assignedCareer')
                            ->orderBy('name')
                            ->get();

        // 3. Generar PDF (Usaremos una vista nueva)
        $pdf = Pdf::loadView('admin.resources.pdf-inventory', compact('resources', 'logoBase64'))
                ->setPaper('a4', 'landscape'); // Landscape es mejor para tantas columnas

        return $pdf->download('inventario-general-' . now()->format('d-m-Y') . '.pdf');
    }


}