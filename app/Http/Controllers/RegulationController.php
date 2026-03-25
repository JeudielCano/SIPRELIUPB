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
                'title' => '01. Laboratorio de Animación 3D y VFX',
                'description' => 'REGLAMENTO INTERNO DEL LABORATORIO DE ANIMACIÓN 3D Y VFX DE LA UNIVERSIDAD POLITÉCNICA DE BACALAR',
                'file' => 'laboratorio_animacion.pdf', // Nombre del archivo
                'date' => '06 de octubre de 2025 ',
            ],

            
            [
                'title' => '02. Laboratorio de Audio y Fotografía',
                'description' => 'REGLAMENTO INTERNO DEL LABORATORIO DE AUDIO Y FOTOGRAFÍA DE LA UNIVERSIDAD POLITÉCNICA DE BACALAR',
                'file' => 'laboratorio_audio_fotografia.pdf',
                'date' => '06 de octubre de 2025 ',
            ],
            [
                'title' => '03. Taller de dibujo',
                'description' => 'REGLAMENTO INTERNO DEL TALLER DE DIBUJO DE LA UNIVERSIDAD POLITÉCNICA DE BACALAR',
                'file' => 'taller_dibujo.pdf',
                'date' => '06 de octubre de 2025',
            ],

            [
                'title' => '04. Taller de Maquetas',
                'description' => 'REGLAMENTO INTERNO DEL TALLER DE MAQUETAS DE LA UNIVERSIDAD POLITÉCNICA DE BACALAR',
                'file' => 'taller_maquetas.pdf',
                'date' => '06 de octubre de 2025',
            ],

            [
                'title' => '05. Laboratorio de Desarrollo de Software',
                'description' => 'REGLAMENTO INTERNO DEL LABORATORIO DE DESARROLLO DE SOFTWARE DE LA UNIVERSIDAD POLITÉCNICA DE BACALAR',
                'file' => 'lab_desarrollo_soft.pdf',
                'date' => '06 de octubre de 2025',
            ],

            [
                'title' => '06. Taller de Electrónica y Sistemas Digitales',
                'description' => 'REGLAMENTO INTERNO DEL TALLER DE ELECTRÓNICA Y SISTEMAS DIGITALES DE LA UNIVERSIDAD POLITÉCNICA DE BACALAR',
                'file' => 'taller_electronica_sistemasD.pdf',
                'date' => '06 de octubre de 2025',
            ],
            
        ];

        return view('regulations.index', compact('regulations'));
    }

    /**
     * Método placeholder para descargar (cuando tengas los archivos).
     */

    public function download($filename)
    {
        // public_path() busca directamente en SIPRELIUPB/public/regulations/
        $path = public_path('regulations/' . $filename);

        // Alerta en caso de no encontrar el archivo
    
        if (!file_exists($path)) {
            abort(404, 'El documento no existe físicamente en la carpeta.');
        }

        // --- EL TRUCO MÁGICO PARA EVITAR PDFs EN BLANCO ---
        // Limpia cualquier espacio en blanco o basura en la memoria de PHP
        // --- LIMPIEZA NUCLEAR DE BÚFER ---
        // Elimina TODOS los niveles de salida ocultos que corrompen el PDF
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // --- SIMPLIFICACIÓN ---
        // Dejamos que Laravel y Symfony calculen las cabeceras exactas automáticamente
        return response()->download($path);
    }
}