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
        // Capturamos la fecha de filtro si existe
        $filterDate = $request->input('date');

        // 1. Obtenemos todos los recursos activos
        $resources = Resource::where('status', '!=', 'mantenimiento')->get();

        // 2. Calculamos el stock real disponible para cada uno
        $resources->transform(function ($resource) use ($filterDate) {
            
            // Consultamos los ítems reservados/prestados
            $reservedCount = LoanItem::where('resource_id', $resource->id)
                ->whereHas('loanRequest', function ($q) use ($filterDate) {
                    // Solo consideramos solicitudes Aprobadas o Activas (las pendientes no bloquean stock aun)
                    $q->whereIn('status', ['aprobado', 'activo']);

                    // SI HAY FILTRO DE FECHA: Verificamos si choca con esa fecha
                    if ($filterDate) {
                        $date = Carbon::parse($filterDate);
                        // Lógica de Solapamiento:
                        // El préstamo inicia ANTES o IGUAL a la fecha filtro
                        // Y termina DESPUÉS o IGUAL a la fecha filtro
                        $q->whereDate('pickup_at', '<=', $date)
                          ->whereDate('due_at', '>=', $date);
                    }
                })
                ->sum('quantity');
            
            // Stock Físico - Reservado = Disponible
            $resource->available_count = max(0, $resource->total_stock - $reservedCount);
            
            return $resource;
        });

        // 3. Agrupamos los recursos por su tipo
        $groupedResources = $resources->groupBy('type');

        return view('catalog.index', compact('groupedResources', 'filterDate'));
    }
}