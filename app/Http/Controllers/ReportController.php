<?php

namespace App\Http\Controllers;

use App\Models\LoanRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Importamos la librería de PDF

class ReportController extends Controller
{
    /**
     * Muestra la lista de todos los préstamos FINALIZADOS (Historial).
     */
    public function index()
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        $finishedLoans = LoanRequest::with(['user', 'activityType', 'subject'])
                            ->where('status', 'finalizado')
                            ->orderBy('return_at', 'desc') // Los más recientes primero
                            ->get();

        return view('admin.reports.index', compact('finishedLoans'));
    }

    /**
     * Muestra el reporte detallado en pantalla (Vista Web).
     */
    public function show(LoanRequest $loan)
    {
        if (auth()->user()->role !== 'administrador') abort(403);
        
        // Validar que esté finalizado
        if ($loan->status !== 'finalizado') {
            return back()->withErrors(['status' => 'Este préstamo aún no ha finalizado.']);
        }

        $loan->load(['user', 'activityType', 'subject', 'items.resource', 'approver']);

        return view('admin.reports.show', compact('loan'));
    }

    /**
     * Genera y descarga el PDF del reporte.
     */
    public function downloadPdf(LoanRequest $loan)
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        if ($loan->status !== 'finalizado') {
            return back()->withErrors(['status' => 'Solo se pueden generar reportes de préstamos finalizados.']);
        }

        $loan->load(['user', 'activityType', 'subject', 'items.resource', 'approver']);

        // Cargamos la vista especial para PDF
        $pdf = Pdf::loadView('admin.reports.pdf', compact('loan'));

        // Descargamos el archivo con un nombre descriptivo
        return $pdf->download('Reporte_Prestamo_UPB_' . $loan->id . '.pdf');
    }
}