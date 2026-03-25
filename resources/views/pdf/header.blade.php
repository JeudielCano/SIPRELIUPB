{{-- resources/views/pdf/header.blade.php --}}
<div class="header-container">
    <table class="logo-table">
        <tr>
            <td class="logo-td">
                @if(isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}" class="logo-img" alt="Logo UPB">
                @else
                    <span style="color: #ccc; font-size: 8px;">[Logo no encontrado]</span>
                @endif
            </td>
            <td class="text-td">
                <div class="header-text">
                    <h1>Universidad Politécnica de Bacalar</h1>
                    <p>Sistema de Préstamo de Recursos (SIPRELI)</p>
                    <p> Actualizacion del reporte </p>
                    <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </td>
        </tr>
    </table>
</div>