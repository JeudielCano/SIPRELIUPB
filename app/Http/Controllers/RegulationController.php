<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegulationController extends Controller
{
    /**
     * Muestra la lista de reglamentos disponibles.
     */
    public function index()
    {
        // Aquí podrías cargar los documentos desde la base de datos en el futuro.
        // Por ahora, definimos una lista estática de ejemplo.
        $regulations = [
            [
                'title' => 'Reglamento General de Laboratorios',
                'description' => 'Normas generales para el uso de las instalaciones y equipos de la universidad.',
                'file' => 'reglamento_general.pdf', // Nombre del archivo (futuro)
                'date' => '2024-01-15',
            ],
            [
                'title' => 'Manual de Seguridad e Higiene',
                'description' => 'Protocolos de seguridad para el manejo de insumos y comportamiento en caso de emergencia.',
                'file' => 'manual_seguridad.pdf',
                'date' => '2023-11-20',
            ],
            [
                'title' => 'Política de Préstamo de Equipos',
                'description' => 'Lineamientos específicos sobre tiempos de entrega, sanciones y cuidado del material.',
                'file' => 'politica_prestamos.pdf',
                'date' => '2024-02-10',
            ],
        ];

        return view('regulations.index', compact('regulations'));
    }

    /**
     * Método placeholder para descargar (cuando tengas los archivos).
     */
    public function download($filename)
    {
        // Lógica futura: return Storage::download('regulations/' . $filename);
        return back()->with('status', 'El archivo aún no está disponible para descarga.');
    }
}