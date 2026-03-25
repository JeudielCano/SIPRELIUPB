<!DOCTYPE html>
<html lang="es">
    <head> <!-- Usamos css para el generador de pdf-->
        <meta charset="UTF-8">
        <title>Reporte de Bajas - SIPRELI</title>
        <style>
            body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; font-size: 11px; margin-top: 10px; }
            
            /* DISEÑO DEL ENCABEZADO CON LOGO */
            .header-container { width: 100%; border-bottom: 2px solid #0056b3; padding-bottom: 10px; margin-bottom: 20px; }
            .logo-table { width: 100%; border: none; border-collapse: collapse; }
            .logo-table td { border: none; padding: 0; vertical-align: middle; }
            
            .logo-td { width: 20%; text-align: left; } /* Espacio para el logo */
            .text-td { width: 80%; text-align: right; } /* Espacio para el texto */
            
            .logo-img { max-height: 70px; max-width: 150px; } /* Ajuste de tamaño del logo */
            
            .header-text h1 { color: #0056b3; margin: 0; font-size: 16px; text-transform: uppercase; }
            .header-text p { margin: 3px 0; color: #555; font-size: 10px; }
            .header-text .report-title { font-size: 12px; font-weight: bold; color: #333; margin-top: 5px; }

            /* ESTILOS DE LA TABLA (Tus estilos anteriores) */
            table { width: 100%; border-collapse: collapse; table-layout: fixed; }
            th { background-color: #f8f9fa; color: #333; font-weight: bold; border: 1px solid #dee2e6; padding: 8px; text-align: left; text-transform: uppercase; font-size: 9px; }
            td { border: 1px solid #dee2e6; padding: 7px; vertical-align: top; word-wrap: break-word; }
            
            .footer { position: fixed; bottom: -30px; left: 0; right: 0; height: 30px; text-align: right; font-size: 9px; color: #999; }
            .tag { background: #f1f1f1; padding: 2px 5px; border-radius: 3px; font-size: 9px; border: 1px solid #ddd; display: inline-block; }
            .text-center { text-align: center; }
            .font-mono { font-family: monospace; font-size: 10px; }
        </style>
    </head>
    <body>
        <div class="header-container"> <!-- Header del pdf -->
            <table class="logo-table">
                <tr>
                    <td class="logo-td">
                        @if($logoBase64)
                            <img src="{{ $logoBase64 }}" class="logo-img" alt="Logo UPB">
                        @else
                            <span style="color: #ccc; font-size: 8px;">[Logo no encontrado]</span>
                        @endif
                    </td>
                    <td class="text-td">
                        <div class="header-text">
                            <h1>Universidad Politécnica de Bacalar</h1>
                            <p>Sistema de Préstamo de Recursos (SIPRELI)</p>
                            <p class="report-title">Reporte de Inventario: Recursos Dados de Baja</p>
                            <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Recurso</th>
                        <th style="width: 15%;">Tipo / Inv.</th>
                        <th style="width: 20%;">Carrera</th>
                        <th style="width: 35%;">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($resources as $index => $resource)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    
                    <td>
                        <div style="font-weight: bold;">{{ $resource->name }}</div>
                    </td>
                    
                    <td>
                        <span class="tag">{{ ucfirst($resource->type) }}</span><br>
                        <span class="font-mono" style="color: #666;">{{ $resource->inventory_number ?? 'S/N' }}</span>
                    </td>
                    
                    <td>{{ $resource->assignedCareer?->name ?? 'General' }}</td>

                    <td style="color: #444; font-size: 10px;">
                        {{-- Probamos con 'description' que es el estándar de tu controlador --}}
                        {{ $resource->description ?? ($resource->observations ?? 'Sin detalles registrados.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>

            <div class="footer">
                Página 1 - Generado el {{ now()->format('d/m/Y H:i') }} - SIPRELI UPB
            </div>
    </body>
</html>