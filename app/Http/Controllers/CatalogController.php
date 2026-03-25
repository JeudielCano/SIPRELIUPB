<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\LoanItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CatalogController extends Controller
{
    /**
     * Muestra el catálogo visual de recursos disponibles.
     */
    public function index(Request $request)
    {
        // 1. Capturamos la palabra que el usuario escribió en el buscador
        $search = $request->input('search');

        // 2. Preparamos las consultas base para cada tipo
        $queryEquipo = Resource::where('type', 'equipo');
        $queryLab = Resource::where('type', 'laboratorio');
        $queryInsumo = Resource::where('type', 'insumo');

        // 3. Si hay una búsqueda, aplicamos el filtro a todas las consultas
        if ($search) {
            $filtro = function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
            };

            $queryEquipo->where($filtro);
            $queryLab->where($filtro);
            $queryInsumo->where($filtro);
        }

        // 4. Ejecutamos la paginación 
        // Usamos appends() para que al cambiar de página, no se borre la búsqueda
        $groupedResources = [
            'equipo' => $queryEquipo->paginate(10, ['*'], 'equipos_page')->appends($request->all()),
            'laboratorio' => $queryLab->paginate(10, ['*'], 'lab_page')->appends($request->all()),
            'insumo' => $queryInsumo->paginate(10, ['*'], 'insumo_page')->appends($request->all()),
        ];

        return view('catalog.index', compact('groupedResources'));
    }
}