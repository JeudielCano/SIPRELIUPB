<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Entrega #{{ $loan->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px; font-size: 10px; }
        
        .section-title { font-size: 14px; font-weight: bold; background-color: #eee; padding: 5px; margin-top: 20px; border-left: 4px solid #333; }
        
        .info-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .info-table td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; color: #555; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 8px; text-align: left; }
        .items-table td { border: 1px solid #ddd; padding: 8px; }
        
        .footer { position: fixed; bottom: 0; left: 0; right: 0; font-size: 10px; text-align: center; border-top: 1px solid #ddd; padding-top: 10px; }
        
        .status-badge { padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; color: white; }
        .status-bueno { background-color: green; }
        .status-danado { background-color: red; }
        .status-perdido { background-color: black; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Universidad Politécnica de Bacalar</h1>
        <p>Sistema de Préstamos de Laboratorios e Insumos (SIPRELI)</p>
        <p><strong>REPORTE DE ENTREGA Y DEVOLUCIÓN DE MATERIAL</strong></p>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <div class="section-title">Datos del Solicitante</div>
                <br>
                <span class="label">Nombre:</span> {{ $loan->user->name }}<br>
                <span class="label">Email:</span> {{ $loan->user->email }}<br>
                <span class="label">Tipo:</span> {{ ucfirst($loan->user->applicant_type) }}<br>
                @if($loan->user->student_id)
                    <span class="label">Matrícula:</span> {{ $loan->user->student_id }}
                @endif
            </td>
            <td width="50%">
                <div class="section-title">Datos del Préstamo #{{ $loan->id }}</div>
                <br>
                <span class="label">Actividad:</span> {{ $loan->activityType->name }}<br>
                <span class="label">Asignatura:</span> {{ $loan->subject->name }}<br>
                <span class="label">Fecha Solicitud:</span> {{ $loan->created_at->format('d/m/Y') }}<br>
                <span class="label">Fecha Devolución:</span> {{ $loan->return_at ? $loan->return_at->format('d/m/Y h:i A') : 'N/A' }}
            </td>
        </tr>
    </table>

    <div class="section-title">Detalle de Recursos Entregados</div>
    <table class="items-table">
        <thead>
            <tr>
                <th>Recurso</th>
                <th>No. Inventario</th>
                <th>Cantidad</th>
                <th>Estado Final</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loan->items as $item)
                <tr>
                    <td>{{ $item->resource->name }}</td>
                    <td>{{ $item->resource->inventory_number ?? '---' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>
                        <span class="status-badge status-{{ $item->return_status }}">
                            {{ strtoupper($item->return_status) }}
                        </span>
                    </td>
                    <td>{{ $item->return_observations ?? 'Ninguna' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br><br>
    <div class="section-title">Firmas de Conformidad</div>
    <br><br><br><br>
    <table style="width: 100%; text-align: center;">
        <tr>
            <td width="50%">__________________________<br>Firma del Solicitante</td>
            <td width="50%">__________________________<br>Firma del Responsable (Admin)</td>
        </tr>
    </table>

    <div class="footer">
        Este documento fue generado automáticamente por el sistema SIPRELI UPB el {{ now()->format('d/m/Y H:i') }}.
    </div>

</body>
</html>