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
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 10px;
        }
        
        th {
            background-color: #f8fafc;
            color: #4a5568;
            text-align: left;
            padding: 8px 10px;
            font-weight: 600;
            font-size: 8pt;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #edf2f7;
            font-size: 8pt;
            vertical-align: top;
        }
        
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
        <table>
            <thead>
                <tr>
                    <th>Activo Fijo</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Tamaño</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Estado</th>
                    <th>Usuario</th>
                    <th>Modificación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td style="font-weight: 500;">{{ $item['item_activo_fijo'] }}</td>
                    <td>{{ $item['item_nombre'] }}</td>
                    <td>{{ Str::limit($item['item_descripcion'], 20) }}</td>
                    <td>{{ $item['item_size'] }}</td>
                    <td>{{ Str::limit($item['item_origen'], 15) }}</td>
                    <td>{{ Str::limit($item['item_destino'], 15) }}</td>
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

    <style>
        .page-number:before {
            content: counter(page);
        }
        .page-count:before {
            content: counter(pages);
        }
    </style>
</body>
</html>