<?php

namespace App\Http\Controllers;

use App\Models\LoanRequest;
use App\Models\LoanItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminLoanController extends Controller
{
    /**
     * Bandeja de Entrada: Solicitudes NORMALES (Internas).
     * Muestra solo las pendientes que NO son externas.
     */
    public function index()
    {
        if (auth()->user()->role !== 'administrador') {
            abort(403, 'Acceso no autorizado.');
        }

        $loans = LoanRequest::with(['user', 'activityType', 'subject'])
                    ->where('status', 'pendiente')
                    // CORRECCIÓN: Incluimos explícitamente las que tienen observaciones NULL (vacías)
                    // O las que tienen texto pero NO dicen "(SOLICITUD EXTERNA)"
                    ->where(function ($query) {
                        $query->whereNull('observations')
                              ->orWhere('observations', 'NOT LIKE', '%(SOLICITUD EXTERNA)%');
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.loans.index', compact('loans'));
    }

    /**
     * Bandeja de Entrada: Solicitudes EXTERNAS.
     * Muestra solo las pendientes que SÍ son externas.
     */
    public function external()
    {
        if (auth()->user()->role !== 'administrador') {
            abort(403, 'Acceso no autorizado.');
        }

        $loans = LoanRequest::with(['user', 'activityType', 'subject'])
                    ->where('status', 'pendiente')
                    // Aquí no hay problema porque LIKE ignora nulos, y solo queremos las que SÍ tienen la marca
                    ->where('observations', 'LIKE', '%(SOLICITUD EXTERNA)%')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.loans.external', compact('loans'));
    }

    /**
     * Muestra la lista de PRÉSTAMOS ACTIVOS (Aprobados y En Curso).
     */
    public function activeLoans()
    {
        if (auth()->user()->role !== 'administrador') {
            abort(403, 'Acceso no autorizado.');
        }

        $loans = LoanRequest::with(['user', 'activityType', 'subject'])
                    ->whereIn('status', ['aprobado', 'activo'])
                    ->orderBy('updated_at', 'desc')
                    ->get();

        return view('admin.loans.active', compact('loans'));
    }

    public function show(LoanRequest $loan)
    {
        if (auth()->user()->role !== 'administrador') abort(403);
        
        $loan->load(['user', 'activityType', 'subject', 'items.resource', 'approver']);
        
        return view('admin.loans.show', compact('loan'));
    }

    public function approve(LoanRequest $loan)
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        // VALIDACIÓN DE STOCK
        foreach ($loan->items as $item) {
            $resource = $item->resource;

            $reservedCount = LoanItem::where('resource_id', $resource->id)
                ->whereHas('loanRequest', function ($q) use ($loan) {
                    $q->whereIn('status', ['aprobado', 'activo'])
                      ->where('id', '!=', $loan->id);
                })
                ->sum('quantity');

            $available = $resource->total_stock - $reservedCount;

            if ($available < $item->quantity) {
                return back()->withErrors([
                    'status' => "Error: No se puede aprobar. El recurso '{$resource->name}' no tiene suficiente stock disponible. (Disponibles reales: {$available}, Solicitados: {$item->quantity})"
                ]);
            }
        }

        $code = strtoupper(Str::random(6));
        
        $loan->update([
            'status' => 'aprobado',
            'approved_by_id' => auth()->id(),
            'pickup_code' => $code,
        ]);

        return back()->with('status', 'Solicitud aprobada. Código de retiro generado: ' . $code);
    }

    public function reject(LoanRequest $loan)
    {
        if (auth()->user()->role !== 'administrador') abort(403);
        
        $loan->update([
            'status' => 'rechazado',
            'approved_by_id' => auth()->id()
        ]);
        
        return back()->with('status', 'Solicitud rechazada.');
    }

    public function deliver(LoanRequest $loan)
    {
        if (auth()->user()->role !== 'administrador') abort(403);
        
        if ($loan->status !== 'aprobado') {
            return back()->withErrors(['status' => 'Solo se pueden entregar solicitudes aprobadas.']);
        }
        
        $loan->update(['status' => 'activo']);
        
        return back()->with('status', 'Material entregado. Préstamo ACTIVO.');
    }

    public function returnForm(LoanRequest $loan)
    {
        if (auth()->user()->role !== 'administrador') abort(403);
        
        if ($loan->status !== 'activo') {
            return redirect()->route('admin.loans.show', $loan)
                ->withErrors(['status' => 'Préstamo no activo.']);
        }
        
        $loan->load(['items.resource', 'user']);
        
        return view('admin.loans.return', compact('loan'));
    }

    public function processReturn(Request $request, LoanRequest $loan)
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        $request->validate([
            'items' => 'required|array',
            'items.*.return_status' => 'required|in:bueno,dañado,perdido',
            'items.*.return_observations' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $loan) {
            
            foreach ($request->items as $itemId => $data) {
                $item = LoanItem::findOrFail($itemId);
                
                if ($item->loan_request_id !== $loan->id) continue;

                $item->update([
                    'return_status' => $data['return_status'],
                    'return_observations' => $data['return_observations'] ?? null,
                ]);

                if (in_array($data['return_status'], ['dañado', 'perdido'])) {
                    $item->resource->decrement('total_stock', $item->quantity);
                }
            }

            $loan->update([
                'status' => 'finalizado',
                'return_at' => now(),
            ]);
        });

        return redirect()->route('admin.loans.show', $loan)
            ->with('status', 'Devolución registrada. Inventario actualizado según el estado de los equipos.');
    }
}