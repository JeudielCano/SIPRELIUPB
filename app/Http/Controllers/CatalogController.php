<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\LoanItem;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Muestra el catálogo visual de recursos disponibles.
     */
    public function index()
    {
        // 1. Obtenemos todos los recursos (excepto los que están en mantenimiento)
        $resources = Resource::where('status', '!=', 'mantenimiento')->get();

        // 2. Calculamos el stock real disponible para cada uno
        $resources->transform(function ($resource) {
            
            // Contamos cuántos están prestados o por recoger
            $reservedCount = LoanItem::where('resource_id', $resource->id)
                ->whereHas('loanRequest', function ($q) {
                    $q->whereIn('status', ['aprobado', 'activo']);
                })
                ->sum('quantity');
            
            // Añadimos una propiedad virtual 'available_count'
            $resource->available_count = max(0, $resource->total_stock - $reservedCount);
            
            return $resource;
        });

        // 3. Agrupamos por tipo para mostrar en pestañas (tabs)
        // Esto crea una colección donde la clave es 'equipo', 'laboratorio', etc.
        $groupedResources = $resources->groupBy('type');

        return view('catalog.index', compact('groupedResources'));
    }
}