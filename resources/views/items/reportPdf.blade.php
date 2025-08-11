<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 9pt;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.4;
        }

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

        table {
            width: 100%;
            table-layout: auto;
            border-collapse: separate;
            border-spacing: 0;
        }

        th, td {
            white-space: normal;
            word-wrap: break-word;
            padding: 6px 8px;
            vertical-align: top;
            font-size: 8pt;
        }

        tr:hover td {
            background-color: #f8fafc;
        }

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

        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 7.5pt;
            font-weight: 500;
        }

        .status-DISPONIBLE {
  background-color: #e6fffa; /* teal-100 */
  color: #285e61;            /* teal-800 */
  border: 1px solid #81e6d9; /* teal-200 */
}

.status-DAÑADA {
  background-color: #fff1f2; /* rose-100 */
  color: #9b2c2c;            /* rose-800 */
  border: 1px solid #fecdd3; /* rose-200 */
}

.status-ASIGNADA {
  background-color: #e0e7ff; /* indigo-100 */
  color: #3730a3;            /* indigo-800 */
  border: 1px solid #c7d2fe; /* indigo-200 */
}

.status-REPARACIÓN {
  background-color: #fffbeb; /* amber-100 */
  color: #92400e;            /* amber-800 */
  border: 1px solid #fde68a; /* amber-200 */
}

.status-RESGUARDO {
  background-color: #fdf4ff; /* fuchsia-100 */
  color: #701a75;            /* fuchsia-800 */
  border: 1px solid #fae8ff; /* fuchsia-200 */
}

.status-SOLO-VIDEO {
  background-color: #ecfeff; /* cyan-100 */
  color: #155e75;            /* cyan-800 */
  border: 1px solid #99f6e4; /* cyan-200 */
}


        .no-data {
            text-align: center;
            padding: 40px;
            color: #a0aec0;
            font-style: italic;
        }

        .tabla-ajustada th:nth-child(1),  .tabla-ajustada td:nth-child(1)  { width: 7%; }  /* Activo Fijo */
        .tabla-ajustada th:nth-child(2),  .tabla-ajustada td:nth-child(2)  { width: 9%; }  /* Nombre */
        .tabla-ajustada th:nth-child(3),  .tabla-ajustada td:nth-child(3)  { width: 6%; }  /* Tag */
        .tabla-ajustada th:nth-child(4),  .tabla-ajustada td:nth-child(4)  { width: 18%; max-width: 180px; } /* Descripción */
        .tabla-ajustada th:nth-child(5),  .tabla-ajustada td:nth-child(5)  { width: 6%; }  /* Tamaño */
        .tabla-ajustada th:nth-child(6),  .tabla-ajustada td:nth-child(6)  { width: 8%; }  /* Origen */
        .tabla-ajustada th:nth-child(7),  .tabla-ajustada td:nth-child(7)  { width: 8%; }  /* Destino */
        .tabla-ajustada th:nth-child(8),  .tabla-ajustada td:nth-child(8)  { width: 7%; }  /* Entrada */
        .tabla-ajustada th:nth-child(9),  .tabla-ajustada td:nth-child(9)  { width: 7%; }  /* Salida */
        .tabla-ajustada th:nth-child(10), .tabla-ajustada td:nth-child(10) { width: 7%; }  /* Estado */
        .tabla-ajustada th:nth-child(11), .tabla-ajustada td:nth-child(11) { width: 10%; } /* Registrado por */
        .tabla-ajustada th:nth-child(12), .tabla-ajustada td:nth-child(12) { width: 10%; } /* Modificado por */
        .tabla-ajustada th:nth-child(13), .tabla-ajustada td:nth-child(13) { width: 10%; } /* Fecha Modificación */
    </style>
</head>
<body>
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
                    <td>{{ $item['item_descripcion'] }}</td>
                    <td>{{ $item['item_size'] }}</td>
                    <td>{{ $item['item_origen'] }}</td>
                    <td>{{ $item['item_destino'] }}</td>
                    <td>{{ $item['item_fecha_entrada'] }}</td>
                    <td>{{ $item['item_fecha_salida'] }}</td>
                    <td>
                        <span class="status status-{{ Str::slug($item['item_status']) }}">
                            {{ $item['item_status'] }}
                        </span>
                    </td>
                    <td>
                        @if(!empty($item['user']))
                            {{ $item['user']['user_name'] }} {{ $item['user']['user_last_name'] }}
                        @else
                            <span style="color: #a0aec0;">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if(!empty($item['user_modifica']))
                            {{ $item['user_modifica']['user_name'] }} {{ $item['user_modifica']['user_last_name'] }}
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
        INVEX • Página <span class="page-number"></span> • {{ now()->format('d/m/Y H:i') }}
    </div>

    <style>
        .page-number:before {
            content: counter(page);
        }
    </style>
</body>
</html>
