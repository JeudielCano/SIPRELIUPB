<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; // <-- IMPORTANTE: Para manejar archivos

class ResourceController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'administrador') abort(403);
        $resources = Resource::all();
        return view('admin.resources.index', ['resources' => $resources]);
    }

    public function create()
    {
        if (auth()->user()->role !== 'administrador') abort(403);
        return view('admin.resources.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['equipo', 'laboratorio', 'insumo'])],
            'inventory_number' => ['nullable', 'string', 'max:255', 'unique:resources,inventory_number'],
            'total_stock' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['disponible', 'prestado', 'mantenimiento'])],
            'image' => ['nullable', 'image', 'max:2048'], // Validar imagen (max 2MB)
            'career' => ['required', Rule::in(['ITID', 'IAEV'])],
        ]);

        // Procesar Subida de Imagen
        if ($request->hasFile('image')) {
            // Guarda en storage/app/public/resources y devuelve la ruta
            $path = $request->file('image')->store('resources', 'public');
            $validatedData['image_path'] = $path;
        }

        Resource::create($validatedData);

        return redirect()->route('admin.resources.index')->with('status', '¡Recurso creado exitosamente!');
    }

    public function edit(string $id)
    {
        if (auth()->user()->role !== 'administrador') abort(403);
        $resource = Resource::findOrFail($id);
        return view('admin.resources.edit', ['resource' => $resource]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        $resource = Resource::findOrFail($id);

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['equipo', 'laboratorio', 'insumo'])],
            'inventory_number' => ['nullable', 'string', 'max:255', Rule::unique('resources', 'inventory_number')->ignore($resource->id)],
            'total_stock' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['disponible', 'prestado', 'mantenimiento'])],
            'image' => ['nullable', 'image', 'max:2048'],
            'career' => ['required', Rule::in(['ITID', 'IAEV'])],
        ]);

        // Procesar Nueva Imagen (y borrar la vieja)
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

    public function destroy(string $id)
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        $resource = Resource::findOrFail($id);
        
        // Borrar imagen al eliminar el recurso
        if ($resource->image_path) {
            Storage::disk('public')->delete($resource->image_path);
        }
        
        $resource->delete();

        return redirect()->route('admin.resources.index')->with('status', '¡Recurso eliminado del inventario!');
    }

    public function show(string $id)
    {
        //
    }
}