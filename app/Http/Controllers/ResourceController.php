<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ResourceController extends Controller
{
    /**
     * Muestra la lista de recursos (Inventario) con filtros.
     */
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'administrador') abort(403);
        
        $query = Resource::query();

        // Filtro por Tipo de Recurso
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtro por Fecha de Alta (created_at)
        if ($request->filled('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('created_at', $date);
        }

        // Ordenar por fecha de creación descendente (lo más nuevo primero)
        $resources = $query->orderBy('created_at', 'desc')->get();
        
        return view('admin.resources.index', ['resources' => $resources]);
    }

    /**
     * Muestra el formulario para dar de alta un nuevo recurso.
     */
    public function create()
    {
        if (auth()->user()->role !== 'administrador') abort(403);
        return view('admin.resources.create');
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
            'career' => ['required', Rule::in(['ITID', 'IAEV'])], // Validación de carrera
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
    public function edit(string $id)
    {
        if (auth()->user()->role !== 'administrador') abort(403);
        $resource = Resource::findOrFail($id);
        return view('admin.resources.edit', ['resource' => $resource]);
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
            'career' => ['required', Rule::in(['ITID', 'IAEV'])],
            'inventory_number' => ['nullable', 'string', 'max:255', Rule::unique('resources', 'inventory_number')->ignore($resource->id)],
            'total_stock' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['disponible', 'prestado', 'mantenimiento'])],
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

    /**
     * Elimina (Da de baja) el recurso.
     */
    public function destroy(string $id)
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        $resource = Resource::findOrFail($id);
        
        // Borrar imagen asociada al eliminar el recurso
        if ($resource->image_path) {
            Storage::disk('public')->delete($resource->image_path);
        }
        
        $resource->delete();

        return redirect()->route('admin.resources.index')->with('status', '¡Recurso dado de baja del inventario!');
    }
}