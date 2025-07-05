<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Ítem - MiApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="{{ asset('js/itemExport.js') }}"></script>
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media (max-width: 640px) {
            .detail-card {
                border-radius: 0.5rem;
                margin: 0.5rem;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            .detail-field {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <main class="flex-grow max-w-7xl mx-auto w-full px-4 py-4">
            <div class="bg-white shadow rounded-lg detail-card">
                <!-- Encabezado -->
                <div class="bg-indigo-50 px-6 py-4 border-b">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">{{ $item['item_nombre'] }}</h2>
                            <p class="text-sm text-gray-600">{{ $item['item_activo_fijo'] }}</p>
                        </div>
                        <span class="mt-2 sm:mt-0 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border @php
                           
                            $statusClasses = [
                                'DISPONIBLE' => 'bg-green-100 text-green-800 border-green-200',
                                'DETENIDA' => 'bg-red-100 text-red-800 border-red-200',
                                'TRABAJANDO' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'POR SALIR' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'COMPRAS' => 'bg-purple-100 text-purple-800 border-purple-200'
                            ];
                            
                            // Normalizamos el estado del ítem a mayúsculas y sin espacios extras
                            $currentStatus = strtoupper(trim($item['item_status']));
                            echo $statusClasses[$currentStatus] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                        @endphp">
                            {{ strtoupper($item['item_status']) }}

                        </span>
                    </div>
                </div>

                <!-- Cuerpo -->
                <div class="divide-y divide-gray-200">
                    <!-- Fila 1 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-0">
                        <div class="detail-field">
                            <h3 class="text-xs font-medium text-gray-500 uppercase">Tag</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $item['item_tag'] ?? 'N/A' }}</p>
                        </div>
                        <div class="detail-field">
                            <h3 class="text-xs font-medium text-gray-500 uppercase">Tamaño</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $item['item_size'] ?? 'N/A' }}</p>
                        </div>
                        <div class="detail-field">
                            <h3 class="text-xs font-medium text-gray-500 uppercase">Origen</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $item['item_origen'] ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Fila 2 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-0">
                        <div class="detail-field">
                            <h3 class="text-xs font-medium text-gray-500 uppercase">Destino</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $item['item_destino'] ?? 'N/A' }}</p>
                        </div>
                        <div class="detail-field">
                            <h3 class="text-xs font-medium text-gray-500 uppercase">Entrada</h3>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($item['item_fecha_entrada'])->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="detail-field">
                            <h3 class="text-xs font-medium text-gray-500 uppercase">Salida</h3>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $item['item_fecha_salida'] ? \Carbon\Carbon::parse($item['item_fecha_salida'])->format('d/m/Y H:i') : 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <!-- Descripción (ancho completo) -->
                    <div class="detail-field">
                        <h3 class="text-xs font-medium text-gray-500 uppercase">Descripción</h3>
                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $item['item_descripcion'] ?? 'N/A' }}</p>
                    </div>

                    <!-- Observaciones (ancho completo) -->
                    <div class="detail-field">
                        <h3 class="text-xs font-medium text-gray-500 uppercase">Observaciones</h3>
                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $item['item_observaciones'] ?? 'Ninguna observación' }}</p>
                    </div>

                    <!-- Última modificación -->
                    <div class="detail-field">
                        <h3 class="text-xs font-medium text-gray-500 uppercase">Última Modificación</h3>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $item['item_fecha_modificacion'] ? \Carbon\Carbon::parse($item['item_fecha_modificacion'])->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                    </div>

                    <!-- Usuario que registró -->
                    <div class="detail-field">
                        <h3 class="text-xs font-medium text-gray-500 uppercase">Registrado por</h3>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $item['user_name'] }} {{ $item['user_last_name'] }}
                        </p>
                    </div>
                    <!-- Usuario que modifico -->
                    <div class="detail-field">
                        <h3 class="text-xs font-medium text-gray-500 uppercase">Modificado por</h3>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $item['user_name_modifier'] }} {{ $item['user_last_name_modifier'] }}
                        </p>
                    </div>
                </div>

                <!-- Pie de página -->
                <div class="bg-gray-50 px-6 py-3 flex justify-between">
                    <button onclick="printItem()" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-print mr-1"></i> Imprimir
                    </button>
                    
                    <div class="flex space-x-2">
                        <button onclick="exportToPDF()" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </button>
                        
                        <a href="{{ route('items.index') }}" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-arrow-left mr-1"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="{{ asset('js/itemExport.js') }}"></script>
</body>
</html>