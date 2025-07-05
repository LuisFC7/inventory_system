<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        /* Estilos base minimalistas */
        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 9pt;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.4;
        }
        
        /* Encabezado elegante */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
        }
        
        .logo {
            height: 40px;
            margin-right: 15px;
        }
        
        .header-info {
            text-align: right;
        }
        
        .header h1 {
            font-size: 14pt;
            margin: 0 0 5px 0;
            color: #2c3e50;
            font-weight: 600;
        }
        
        .header p {
            font-size: 8pt;
            margin: 2px 0;
            color: #7f8c8d;
        }
        
        /* Tabla moderna */
        table {
            width: 100%;
            table-layout: fixed; /* fijo para respetar anchos */
            border-collapse: separate;
            border-spacing: 0;
        }

        td, th {
            white-space: normal; /* permite que el texto se divida en varias líneas */
            word-wrap: break-word; /* romper palabras largas */
            padding: 8px 10px;
            vertical-align: top;
            font-size: 8pt;
        }

        /* Anchos de columna definidos */
        .tabla-ajustada th:nth-child(1), .tabla-ajustada td:nth-child(1) { width: 6%; }  /* Activo Fijo */
        .tabla-ajustada th:nth-child(2), .tabla-ajustada td:nth-child(2) { width: 8%; }  /* Nombre */
        .tabla-ajustada th:nth-child(3), .tabla-ajustada td:nth-child(3) { width: 6%; }  /* Tag */
        .tabla-ajustada th:nth-child(4), .tabla-ajustada td:nth-child(4) { width: 20%; } /* Descripción */
        .tabla-ajustada th:nth-child(5), .tabla-ajustada td:nth-child(5) { width: 6%; }  /* Tamaño */
        .tabla-ajustada th:nth-child(6), .tabla-ajustada td:nth-child(6) { width: 8%; }  /* Origen */
        .tabla-ajustada th:nth-child(7), .tabla-ajustada td:nth-child(7) { width: 8%; }  /* Destino */
        .tabla-ajustada th:nth-child(8), .tabla-ajustada td:nth-child(8) { width: 6%; }  /* Entrada */
        .tabla-ajustada th:nth-child(9), .tabla-ajustada td:nth-child(9) { width: 6%; }  /* Salida */
        .tabla-ajustada th:nth-child(10), .tabla-ajustada td:nth-child(10) { width: 6%; } /* Estado */
        .tabla-ajustada th:nth-child(11), .tabla-ajustada td:nth-child(11) { width: 10%; } /* Registrado por */
        .tabla-ajustada th:nth-child(12), .tabla-ajustada td:nth-child(12) { width: 6%; } /* Modificado por */
        .tabla-ajustada th:nth-child(13), .tabla-ajustada td:nth-child(13) { width: 8%; } /* Fecha Modificación */

        tr:hover td {
            background-color: #f8fafc;
        }
        
        /* Pie de página discreto */
        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #a0aec0;
            padding-top: 10px;
            border-top: 1px solid #eaeaea;
        }
        
        /* Estados con badges */
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 7.5pt;
            font-weight: 500;
        }
        
        .status-DISPONIBLE { background-color: #e6fffa; color: #38b2ac; }
        .status-DETENIDA { background-color: #fff5f5; color: #f56565; }
        .status-TRABAJANDO { background-color: #ebf8ff; color: #4299e1; }
        .status-POR-SALIR { background-color: #fffaf0; color: #ed8936; }
        .status-COMPRAS { background-color: #faf5ff; color: #9f7aea; }
        
        /* Sin datos */
        .no-data {
            text-align: center;
            padding: 40px;
            color: #a0aec0;
            font-style: italic;
        }
        
        /* Numeración de páginas PDF */
        .page-number:before {
            content: counter(page);
        }
        .page-count:before {
            content: counter(pages);
        }
    </style>
</head>
<body>
    <!-- Encabezado minimalista -->
    <div class="header">
        <div class="logo-container">
            <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('icons/warehouse-stock-svgrepo-com.svg'))) }}" class="logo" alt="Logo INVEX">
            <div>
                <h1>INVEX</h1>
                <p>Sistema de Gestión de Inventarios</p>
            </div>
        </div>
        <div class="header-info">
            <h1>{{ $title }}</h1>
            <p>{{ $date }}</p>
            <p>Generado por: {{ Auth::user()->user_name }} {{ Auth::user()->user_last_name }}</p>
        </div>
    </div>

    <!-- Tabla moderna -->
    @if($items->isEmpty())
        <p class="no-data">No hay datos disponibles para mostrar</p>
    @else
        <table class="tabla-ajustada">
            <thead>
                <tr>
                    <th>Activo Fijo</th>
                    <th>Nombre</th>
                    <th>Tag</th>
                    <th>Descripción</th>
                    <th>Tamaño</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Estado</th>
                    <th>Registrado por</th>
                    <th>Modificado por</th>
                    <th>Modificación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td style="font-weight: 500;">{{ $item['item_activo_fijo'] }}</td>
                    <td>{{ $item['item_nombre'] }}</td>
                    <td>{{ $item['item_tag'] }}</td>
                    <td style="white-space: normal; word-wrap: break-word;">{{ $item['item_descripcion'] }}</td>
                    <td>{{ $item['item_size'] }}</td>
                    <td>{{ $item['item_origen'] }}</td> <!-- aquí mostramos completo sin Str::limit -->
                    <td>{{ $item['item_destino'] }}</td> <!-- aquí mostramos completo sin Str::limit -->
                    <td>{{ $item['item_fecha_entrada'] }}</td>
                    <td>{{ $item['item_fecha_salida'] }}</td>
                    <td>
                        <span class="status status-{{ Str::slug($item['item_status']) }}">
                            {{ $item['item_status'] }}
                        </span>
                    </td>
                    <td>
                        @if(!empty($item['user']))
                            {{ Str::limit(trim($item['user']['user_name'].' '.$item['user']['user_last_name']), 15) }}
                        @else
                            <span style="color: #a0aec0;">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if(!empty($item['user_modifica']))
                            {{ Str::limit(trim($item['user_modifica']['user_name'] . ' ' . $item['user_modifica']['user_last_name']), 15) }}
                        @else
                            <span style="color: #a0aec0;">N/A</span>
                        @endif
                    </td>
                    <td>{{ $item['item_fecha_modificacion'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
    <div class="footer">
        @php
            $appName = config('app.name', 'INVEX');
        @endphp
        INVEX • Página <span class="page-number"></span> • {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
