<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario General - SIPRELI</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; font-size: 10px; margin: 0; }
        
        /* Reutilizamos tu Header Institucional */
        .header-container { width: 100%; border-bottom: 2px solid #0056b3; padding-bottom: 10px; margin-bottom: 20px; }
        .logo-table { width: 100%; border-collapse: collapse; }
        .logo-table td { border: none !important; vertical-align: middle; }
        .header-text h1 { color: #0056b3; margin: 0; font-size: 16px; text-transform: uppercase; }
        .header-text p { margin: 2px 0; color: #555; font-size: 9px; }

        /* Estilos de la Tabla de Inventario */
        table.inventory-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .inventory-table th { background-color: #f2f2f2; border: 1px solid #ccc; padding: 8px; text-align: left; text-transform: uppercase; font-size: 9px; }
        .inventory-table td { border: 1px solid #ccc; padding: 6px; vertical-align: middle; }
        
        .text-center { text-align: center; }
        .badge { padding: 3px 6px; border-radius: 4px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .status-disponible { background-color: #dcfce7; color: #166534; }
        .status-prestado { background-color: #fef9c3; color: #854d0e; }
        
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; text-align: right; font-size: 8px; color: #999; }
    </style>
</head>
<body>

    <div class="header-container">
        <table class="logo-table">
            <tr>
                <td style="width: 120px;">
                    @if($logoBase64) <img src="{{ $logoBase64 }}" style="height: 60px;"> @endif
                </td>
                <td style="text-align: right;">
                    <div class="header-text">
                        <h1>Universidad Politécnica de Bacalar</h1>
                        <p>SIPRELI — Sistema de Préstamo de Recursos</p>
                        <p><strong>REPORTE DE INVENTARIO GENERAL</strong></p>
                        <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Nombre del Recurso</th>
                <th>Tipo</th>
                <th>Carrera</th>
                <th>No. Inventario</th>
                <th class="text-center">Estado</th>
                <th class="text-center">Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resources as $index => $res)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td style="font-weight: bold;">{{ $res->name }}</td>
                <td>{{ ucfirst($res->type) }}</td>
                <td>{{ $res->assignedCareer?->name ?? 'General' }}</td>
                <td style="font-family: monospace;">{{ $res->inventory_number ?? 'S/N' }}</td>
                <td class="text-center">
                    <span class="badge {{ $res->status == 'disponible' ? 'status-disponible' : 'status-prestado' }}">
                        {{ str_replace('_', ' ', $res->status) }}
                    </span>
                </td>
                <td class="text-center"><strong>{{ $res->total_stock }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        SIPRELI UPB - Inventario de Recursos - Página 1
    </div>
</body>
</html>