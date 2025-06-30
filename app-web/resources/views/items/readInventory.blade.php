<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Inventario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media (max-width: 640px) {
            .mobile-card {
                border: 1px solid #e2e8f0;
                border-radius: 0.5rem;
                padding: 1rem;
                margin-bottom: 1rem;
                background: white;
            }
            .mobile-container {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
       
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                <div class="flex items-center">
                    <img class="h-8 w-auto" src="{{ asset('icons/warehouse-stock-svgrepo-com.svg') }}" alt="Logo">
                    <span class="ml-2 text-xl font-semibold text-gray-900">INVEX</span>
                </div>
                <div class="flex items-center space-x-4">
                    
                    <!-- Menú de usuario -->
                    <div class="relative">
                        <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-medium">{{ substr(Auth::user()->user_name, 0, 2) }}</span>
                            </div>
                            <span class="hidden md:inline text-gray-700">{{ Auth::user()->user_name }}</span>
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </button>
                        
                        <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                            <div class="px-4 py-2 border-b">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-indigo-600 font-medium">{{ substr(Auth::user()->user_name, 0, 2) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->user_name }} {{ Auth::user()->user_last_name }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->user_email }}</p>
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2 text-gray-400"></i> Configuración
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-boxes mr-2 text-gray-400"></i> Inventario
                            </a>
                            <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2 text-gray-400"></i> Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Contenido principal -->
        <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mobile-container">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <h1 class="text-2xl font-bold text-gray-900">Consulta de Inventario</h1>

                    <form method="GET" action="{{ route('items.index') }}" class="relative w-full md:w-64">
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" 
                            placeholder="Buscar..." 
                            class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </form>


                </div>

                <!-- Versión para desktop -->
                <div class="hidden md:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activo Fijo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tag</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detalle</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->item_activo_fijo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->item_nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->item_tag ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClasses = [
                                                'activo' => 'bg-green-100 text-green-800',
                                                'inactivo' => 'bg-gray-100 text-gray-800',
                                                'mantenimiento' => 'bg-yellow-100 text-yellow-800',
                                                'baja' => 'bg-red-100 text-red-800'
                                            ];
                                            $class = $statusClasses[strtolower($item->item_status)] ?? 'bg-blue-100 text-blue-800';
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                            {{ ucfirst($item->item_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('items.show', $item->item_id) }}" class="text-indigo-600 hover:text-indigo-900" title="Ver detalle">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Versión para móvil - Solo consulta -->
                <div class="md:hidden">
                    @foreach($items as $item)
                    <div class="mobile-card">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-gray-900">{{ $item->item_activo_fijo }}</p>
                                <p class="text-gray-600">{{ $item->item_nombre }}</p>
                            </div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @php
                                    $statusClasses = [
                                        'activo' => 'bg-green-100 text-green-800',
                                        'inactivo' => 'bg-gray-100 text-gray-800',
                                        'mantenimiento' => 'bg-yellow-100 text-yellow-800',
                                        'baja' => 'bg-red-100 text-red-800'
                                    ];
                                    echo $statusClasses[strtolower($item->item_status)] ?? 'bg-blue-100 text-blue-800';
                                @endphp">
                                {{ ucfirst($item->item_status) }}
                            </span>
                        </div>
                        
                        <div class="mt-2">
                            <p class="text-xs text-gray-500">Tag</p>
                            <p class="text-sm">{{ $item->item_tag ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('items.show', $item->item_id) }}" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center" title="Ver detalle">
                                <i class="fas fa-eye mr-1"></i> Ver detalles
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                @if($items->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Mostrando <span class="font-medium">{{ $items->firstItem() }}</span> a <span class="font-medium">{{ $items->lastItem() }}</span> de <span class="font-medium">{{ $items->total() }}</span> resultados
                    </div>
                    <div class="flex space-x-2">
                        @if($items->onFirstPage())
                            <span class="px-3 py-1 border rounded-md text-sm font-medium text-gray-300 bg-white cursor-not-allowed">Anterior</span>
                        @else
                            <a href="{{ $items->previousPageUrl() }}" class="px-3 py-1 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Anterior</a>
                        @endif

                        @if($items->hasMorePages())
                            <a href="{{ $items->nextPageUrl() }}" class="px-3 py-1 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Siguiente</a>
                        @else
                            <span class="px-3 py-1 border rounded-md text-sm font-medium text-gray-300 bg-white cursor-not-allowed">Siguiente</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </main>
    </div>

    <script>
        // Control del menú desplegable
        document.getElementById('userMenuButton').addEventListener('click', function() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
        });

        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('userMenu');
            const button = document.getElementById('userMenuButton');
            
            if (menu && button && !menu.contains(event.target) && !button.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // Búsqueda en tiempo real
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            
            // Para la vista de desktop
            const desktopRows = document.querySelectorAll('.min-w-full tbody tr');
            desktopRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
            
            // Para la vista móvil
            const mobileCards = document.querySelectorAll('.mobile-card');
            mobileCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });

        function openModal(itemId) {
        fetch(`/items/${itemId}/details`) // Esta ruta la crearemos después
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalTitle').textContent = `Detalles: ${data.item_nombre}`;
                
                const modalContent = document.getElementById('modalContent');
                modalContent.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Activo Fijo</p>
                            <p class="text-base">${data.item_activo_fijo}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nombre</p>
                            <p class="text-base">${data.item_nombre}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tag</p>
                            <p class="text-base">${data.item_tag || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tamaño</p>
                            <p class="text-base">${data.item_size || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Origen</p>
                            <p class="text-base">${data.item_origen || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Destino</p>
                            <p class="text-base">${data.item_destino || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Fecha Entrada</p>
                            <p class="text-base">${new Date(data.item_fecha_entrada).toLocaleDateString()}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Fecha Salida</p>
                            <p class="text-base">${data.item_fecha_salida ? new Date(data.item_fecha_salida).toLocaleDateString() : 'N/A'}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-500">Observaciones</p>
                            <p class="text-base">${data.item_observaciones || 'Sin observaciones'}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Estado</p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                ${getStatusClass(data.item_status)}">
                                ${data.item_status.charAt(0).toUpperCase() + data.item_status.slice(1)}
                            </span>
                        </div>
                    </div>
                `;
                
                document.getElementById('itemModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los detalles del ítem');
            });
    }

    function closeModal() {
        document.getElementById('itemModal').classList.add('hidden');
    }

    function getStatusClass(status) {
        const statusClasses = {
            'activo': 'bg-green-100 text-green-800',
            'inactivo': 'bg-gray-100 text-gray-800',
            'mantenimiento': 'bg-yellow-100 text-yellow-800',
            'baja': 'bg-red-100 text-red-800'
        };
        return statusClasses[status.toLowerCase()] || 'bg-blue-100 text-blue-800';
    }

    // Cerrar modal al hacer clic fuera del contenido
    window.onclick = function(event) {
        const modal = document.getElementById('itemModal');
        if (event.target === modal) {
            closeModal();
        }
    }
    </script>
</body>
</html>