<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        h1 { font-size: 16px; margin-bottom: 4px; }
        .meta { font-size: 10px; color: #666; margin-bottom: 16px; }
        .stats { display: flex; gap: 12px; margin-bottom: 16px; }
        .stat-box { border: 1px solid #ddd; padding: 8px 16px; border-radius: 6px; text-align: center; }
        .stat-box .num { font-size: 20px; font-weight: bold; }
        .stat-box .label { font-size: 9px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #1e3a5f; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 10px; }
        tr:nth-child(even) { background: #f9f9f9; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-finalizado { background: #e5e7eb; color: #374151; }
        .badge-activo     { background: #d1fae5; color: #065f46; }
        .badge-aprobado   { background: #dbeafe; color: #1e40af; }
        .badge-rechazado  { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 20px; font-size: 9px; color: #999; text-align: center; }
        /* Estilos para el Header con logo */
        .header-container { 
            width: 100%; 
            max-width: 100%; 
            margin: 0 auto 15px auto; /* Centrado y con menos margen inferior */
            border-bottom: 2px solid #0056b3; 
            padding-bottom: 8px;
        }

        .logo-table { 
            width: 100%; 
            border-collapse: collapse; 
            border: none !important;
        }

        .logo-table td { 
            border: none !important; 
            padding: 0 !important; 
            vertical-align: middle; 
        }

        /* AJUSTE DEL LOGOTIPO */
        .logo-td { 
            width: 120px; /* Fijamos un ancho para que no empuje el texto */
            text-align: left; 
        }

        .logo-img { 
            height: 55px; /* Altura fija para que sea discreto */
            width: auto;
            display: block;
        }

        /* AJUSTE DEL TEXTO */
        .text-td { 
            text-align: right; 
        }

        .header-text h1 { 
            color: #0056b3; 
            margin: 0; 
            font-size: 15px; /* Un poco más pequeño */
            text-transform: uppercase; 
        }

        .header-text p { 
            margin: 1px 0; 
            color: #555; 
            font-size: 9px; 
        }

        .header-text .report-title { 
            font-size: 11px; 
            font-weight: bold; 
            color: #333; 
            margin-top: 3px; 
            text-transform: none;
        }
        /* Fin de los estilos para el Header con logo */
    </style>
</head>
<body>
    <!--Para llamar la plantilla del header-->
    {{-- Llamamos a la plantilla compartida --}}
    @include('pdf.header', ['tituloReporte' => 'Reporte de Inventario: Recursos Dados de Baja'])
    <!--Fin de plantilla del header-->

    <h1> Reporte de Subresguardo — {{ $guardianName }}</h1>
    <p class="meta">Generado el: {{ $fecha }} | Universidad Politécnica de Bacalar</p>

    {{-- Estadísticas --}}
    <table style="width: auto; margin-bottom: 16px;">
        <tr>
            <td style="padding: 6px 16px; border: 1px solid #ddd; text-align:center;">
                <div style="font-size:18px; font-weight:bold;">{{ $stats['total'] }}</div>
                <div style="font-size:9px; color:#666;">Total</div>
            </td>
            <td style="padding: 6px 16px; border: 1px solid #ddd; text-align:center;">
                <div style="font-size:18px; font-weight:bold; color:#065f46;">{{ $stats['finalizados'] }}</div>
                <div style="font-size:9px; color:#666;">Finalizados</div>
            </td>
            <td style="padding: 6px 16px; border: 1px solid #ddd; text-align:center;">
                <div style="font-size:18px; font-weight:bold; color:#1e40af;">{{ $stats['activos'] }}</div>
                <div style="font-size:9px; color:#666;">En curso</div>
            </td>
            <td style="padding: 6px 16px; border: 1px solid #ddd; text-align:center;">
                <div style="font-size:18px; font-weight:bold; color:#991b1b;">{{ $stats['rechazados'] }}</div>
                <div style="font-size:9px; color:#666;">Rechazados</div>
            </td>
        </tr>
    </table>

    {{-- Tabla de préstamos --}}
    <table>
        <thead>
            <tr>
                <th>Folio</th>
                <th>Solicitante</th>
                <th>Tipo</th>
                <th>Recursos</th>
                <th>Fecha Retiro</th>
                <th>Fecha Devolución</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $loan)
                <tr>
                    <td>#{{ $loan->id }}</td>
                    <td>{{ $loan->user->name }}</td>
                    <td>{{ ucfirst($loan->user->applicant_type) }}</td>
                    <td>
                        @foreach($loan->items as $item)
                            {{ $item->resource->name }} ({{ $item->quantity }})@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td>{{ $loan->pickup_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $loan->return_at ? $loan->return_at->format('d/m/Y H:i') : $loan->due_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge badge-{{ $loan->status }}">
                            {{ ucfirst($loan->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:#999;">Sin registros</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="footer">SIPRELI UPB — Sistema de Préstamo de Recursos Universitarios</p>

</body>
</html>