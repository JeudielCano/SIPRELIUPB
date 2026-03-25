<!DOCTYPE html>
<html lang="es">
<head>

<!--    Con este codigo le damos el formato al pdf descargable para crear el permiso imprimible.    -->

    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1a1a1a; padding: 30px; }

        .header { text-align: center; margin-bottom: 24px; border-bottom: 3px solid #1e3a5f; padding-bottom: 16px; }
        .header h1 { font-size: 18px; font-weight: bold; color: #1e3a5f; margin-bottom: 4px; }
        .header h2 { font-size: 13px; font-weight: normal; color: #444; margin-bottom: 4px; }
        .header p  { font-size: 11px; color: #666; }

        .titulo-doc { text-align: center; margin: 20px 0 16px; }
        .titulo-doc h3 { font-size: 15px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #1e3a5f; }
        .titulo-doc p  { font-size: 11px; color: #666; margin-top: 4px; }

        .seccion { margin-bottom: 20px; }
        .seccion h4 { font-size: 11px; font-weight: bold; text-transform: uppercase; color: #fff; background: #1e3a5f; padding: 5px 10px; margin-bottom: 10px; border-radius: 3px; }

        .grid-2 { display: table; width: 100%; }
        .col { display: table-cell; width: 50%; padding-right: 16px; vertical-align: top; }
        .col:last-child { padding-right: 0; }

        .campo { margin-bottom: 12px; }
        .campo label { font-size: 10px; color: #666; text-transform: uppercase; display: block; margin-bottom: 3px; }
        .campo .valor { border-bottom: 1px solid #999; padding: 4px 2px; font-size: 12px; min-height: 22px; }
        .campo .valor.filled { color: #1a1a1a; font-weight: bold; }
        .campo .valor.empty { color: #bbb; font-style: italic; }

        .aviso { background: #fff8e1; border-left: 4px solid #f59e0b; padding: 10px 14px; margin: 20px 0; border-radius: 2px; }
        .aviso p { font-size: 11px; color: #92400e; line-height: 1.6; }
        .aviso strong { display: block; margin-bottom: 4px; font-size: 12px; }

        .firmas { display: table; width: 100%; margin-top: 40px; }
        .firma { display: table-cell; width: 33%; text-align: center; padding: 0 10px; vertical-align: bottom; }
        .firma .linea { border-top: 1px solid #333; margin-bottom: 6px; }
        .firma p { font-size: 10px; color: #444; line-height: 1.5; }
        .firma .nombre { font-weight: bold; font-size: 11px; }

        .folio { text-align: right; font-size: 10px; color: #999; margin-bottom: 8px; }
        .footer { text-align: center; font-size: 9px; color: #aaa; margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; }

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

    {{-- Folio --}}
    <div class="folio">Fecha de emisión: {{ $fecha }}</div>

    {{-- Tabla contenedora del Encabezado --}}
    <table style="width: 100%; border: none; border-collapse: collapse; margin-top: 10px;">
        <tr>
            {{-- Celda del Logotipo --}}
            <td style="width: 120px; border: none; vertical-align: middle;">
                @if(isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}" style="height: 65px; width: auto; display: block;">
                @else
                    <div style="font-size: 8px; color: #ccc;">[Logo]</div>
                @endif
            </td>

            {{-- Celda de la Información (Tu formato original) --}}
            <td style="border: none; vertical-align: middle; padding-left: 15px;">
                <div class="header" style="text-align: left; margin: 0; border: none;">
                    <h1 style="margin: 0; font-size: 18px;">Universidad Politécnica de Bacalar</h1>
                    <h2 style="margin: 2px 0; font-size: 14px;">Sistema de Préstamo de Recursos Universitarios — SIPRELI UPB</h2>
                    <p style="margin: 0; font-size: 10px; color: #555;">Avenida 39, REG 12 MZ 325 LT 1, C.P. 77930, Bacalar, Q.Roo. Tel: 983 128 1591</p>
                </div>
            </td>
        </tr>
    </table>
    {{-- Línea divisoria opcional para mantener el estilo --}}
    <hr style="border: none; border-top: 2px solid #0056b3; margin-top: 10px;">

    {{-- Título del documento --}}
    <div class="titulo-doc">
        <h3>Permiso de Préstamo Externo</h3>
        <p>Este documento debe ser firmado y presentado al momento de recoger el material.</p>
    </div>
    
    {{-- Datos del solicitante --}}
    <div class="seccion">
        <h4>Datos del Solicitante</h4>
        <div class="grid-2">
            <div class="col">
                <div class="campo">
                    <label>Nombre Completo</label>
                    <div class="valor filled">{{ $user->name }}</div>
                </div>
                <div class="campo">
                    <label>Correo Electrónico</label>
                    <div class="valor filled">{{ $user->email }}</div>
                </div>
                <div class="campo">
                    <label>Teléfono</label>
                    <div class="valor {{ $user->phone_number ? 'filled' : 'empty' }}">
                        {{ $user->phone_number ?? 'No registrado' }}
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="campo">
                    <label>Tipo de Solicitante</label>
                    <div class="valor filled">{{ ucfirst($user->applicant_type) }}</div>
                </div>
                @if($user->student_id)
                    <div class="campo">
                        <label>Matrícula</label>
                        <div class="valor filled">{{ $user->student_id }}</div>
                    </div>
                @endif
                <div class="campo">
                    <label>Fecha de Emisión</label>
                    <div class="valor filled">{{ $fecha }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Datos del préstamo --}}
    <div class="seccion">
        <h4>Datos del Préstamo</h4>
        <div class="grid-2">
            <div class="col">
                <div class="campo">
                    <label>Recursos Solicitados</label>
                    @forelse($resources as $recurso)
                        <div class="valor filled">{{ $recurso }}</div>
                    @empty
                        <div class="valor empty">No especificado</div>
                    @endforelse
                </div>
                <div class="campo">
                    <label>Actividad / Propósito</label>
                    <div class="valor {{ $activityType ? 'filled' : 'empty' }}">
                        {{ $activityType ?? 'No especificado' }}
                    </div>
                </div>
                <div class="campo">
                    <label>Asignatura</label>
                    <div class="valor {{ $subject ? 'filled' : 'empty' }}">
                        {{ $subject ?? 'No especificada' }}
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="campo">
                    <label>Fecha y Hora de Retiro</label>
                    <div class="valor {{ $pickup_at ? 'filled' : 'empty' }}">
                        {{ $pickup_at ?? 'No especificada' }}
                    </div>
                </div>
                <div class="campo">
                    <label>Fecha y Hora de Devolución</label>
                    <div class="valor {{ $due_at ? 'filled' : 'empty' }}">
                        {{ $due_at ?? 'No especificada' }}
                    </div>
                </div>
                <div class="campo">
                    <label>Observaciones</label>
                    <div class="valor {{ $observations ? 'filled' : 'empty' }}">
                        {{ $observations ?? 'Ninguna' }}
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Aviso y condiciones --}}
    <div class="aviso">
        <p>
            <strong>⚠️ Términos y Condiciones del Préstamo Externo</strong>
            El solicitante se compromete a: (1) Devolver el material en las mismas condiciones en que fue entregado,
            en la fecha y hora acordadas. (2) El préstamo externo tiene una duración máxima de <strong>4 días naturales</strong>.
            (3) En caso de daño o pérdida del material, el solicitante será responsable y debera acordar con el administrador la resolución.
            (4) El incumplimiento de estas condiciones podrá resultar en la desactivación de la cuenta del sistema.
        </p>
    </div>

    <br>
    <br>

    {{-- Firmas --}}
    <div class="firmas">
        <div class="firma">
            <p></p>
            <div class="linea"></div>
            <p class="nombre">{{ $user->name }}</p>
            <p>Solicitante</p>
            <p>{{ ucfirst($user->applicant_type) }}</p>
        </div>

        <div class="firma">
            <p></p>
            <div class="linea"></div>
            <p class="nombre">JULIO MANUEL CEN CAN</p>
            <p>Firma Y/O Sello</p>
            <p>Coordinador de las ingenierias</p>
        </div>

        <div class="firma">
            <div class="linea"></div>
            <p class="nombre">Responsable de la entrega</p>
            <p>Por favor, escriba su </p>
            <p>nombre y firma</p>
        </div>

    </div>

    <div class="footer">
        SIPRELI UPB — Sistema de prestramos en linea de la Universidad Politécnica de Bacalar
    </div>

</body>
</html>