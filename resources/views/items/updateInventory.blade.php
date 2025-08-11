<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Inventario - INVEX</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <a href="{{ route('dashboard') }}" class="flex items-center mr-3 group">
                        <!-- Icono para móviles y tablets -->
                        <span class="md:hidden text-gray-600 hover:text-gray-900 mr-1">
                            <i class="fas fa-arrow-left"></i>
                        </span>
                        <!-- Texto + icono para desktop -->
                        <span class="hidden md:flex items-center text-indigo-600 hover:text-indigo-800">
                            <i class="fas fa-home mr-1"></i>
                            <span class="text-sm">Inicio</span>
                        </span>
                    </a>
                    
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
                            <!-- <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2 text-gray-400"></i> Configuración
                            </a> -->
                            <a href="{{ route('items.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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
                    <h1 class="text-2xl font-bold text-gray-900">Actualización de Inventario</h1>

                    <form method="GET" action="{{ route('items.indexUpdateForm') }}" class="relative w-full md:w-64 flex items-center">
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" 
                            placeholder="Buscar..." 
                            class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        
                        <!-- Botón de limpiar (solo visible cuando hay búsqueda) -->
                        @if(request('search'))
                            <a href="{{ route('items.indexUpdateForm') }}" 
                            class="ml-2 p-2 text-gray-500 hover:text-gray-700"
                            title="Limpiar búsqueda">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
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
                                            // Definimos las clases para cada estado (en mayúsculas para consistencia)
                                            $statusClasses = [
                                                'DISPONIBLE' => 'bg-green-100 text-green-800 border-green-200',
                                                'DETENIDA' => 'bg-red-100 text-red-800 border-red-200',
                                                'TRABAJANDO' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'POR SALIR' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                'COMPRAS' => 'bg-purple-100 text-purple-800 border-purple-200'
                                            ];
                                            
                                            // Normalizamos el estado del ítem a mayúsculas y sin espacios extras
                                            $currentStatus = strtoupper(trim($item->item_status));
                                            $class = $statusClasses[$currentStatus] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                            {{ ucfirst($item->item_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-y-2">
                                        <a 
                                            href="{{ route('items.edit', $item->item_id) }}" 
                                            class="flex items-center px-3 py-2 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 rounded-md transition-colors"
                                            title="Editar item"
                                        >
                                            <i class="fas fa-edit mr-2"></i> 
                                            Editar
                                        </a>

                                        <form method="POST" action="{{ route('items.deactivate', $item->item_id) }}" onsubmit="return confirmDelete(event)" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button 
                                                type="submit"
                                                class="flex items-center px-3 py-2 bg-red-100 text-red-600 hover:bg-red-200 rounded-md transition-colors"
                                                title="Eliminar item"
                                            >
                                                <i class="fas fa-trash mr-2"></i> Eliminar
                                            </button>
                                        </form>
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
                                        'DISPONIBLE' => 'bg-green-100 text-green-800 border-green-200',
                                        'DETENIDA' => 'bg-red-100 text-red-800 border-red-200',
                                        'TRABAJANDO' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'POR SALIR' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'COMPRAS' => 'bg-purple-100 text-purple-800 border-purple-200'
                                    ];
                                    $currentStatus = strtoupper(trim($item->item_status));
                                    echo $statusClasses[$currentStatus] ?? 'bg-blue-100 text-blue-800 border-blue-200';
                                @endphp">
                                {{ ucfirst($item->item_status) }}
                            </span>
                        </div>
                        
                        <div class="mt-2">
                            <p class="text-xs text-gray-500">Tag</p>
                            <p class="text-sm">{{ $item->item_tag ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="mt-3 flex flex-col gap-2">
                            <a 
                                href="{{ route('items.edit', $item->item_id) }}" 
                                class="flex items-center px-3 py-2 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 rounded-md transition-colors w-full justify-center"
                                title="Editar item"
                            >
                                <i class="fas fa-edit mr-2"></i> 
                                Editar
                            </a>

                            <form method="POST" action="{{ route('items.deactivate', $item->item_id) }}" onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('PATCH')
                                <button 
                                    type="submit"
                                    class="flex items-center px-3 py-2 bg-red-100 text-red-600 hover:bg-red-200 rounded-md transition-colors w-full justify-center"
                                    title="Eliminar item"
                                >
                                    <i class="fas fa-trash mr-2"></i> Eliminar
                                </button>
                            </form>
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

        // Implementación de debounce para la búsqueda
        document.getElementById('searchInput').addEventListener('input', function() {
            // Cancelar el timeout anterior si existe
            clearTimeout(this.searchTimeout);
            
            // Configurar un nuevo timeout
            const searchForm = this.form;
            const searchValue = this.value.trim();
            
            const spinner = document.createElement('div');
            spinner.className = 'absolute right-3 top-3 hidden';
            spinner.innerHTML = '<i class="fas fa-spinner fa-spin text-gray-400"></i>';
            this.form.appendChild(spinner);

            // Si el campo está vacío, enviar el formulario inmediatamente
            if (searchValue === '') {
                searchForm.submit();
                return;
            }
            
            // Esperar 500ms después de la última tecla para enviar
            this.searchTimeout = setTimeout(() => {
                spinner.classList.remove('hidden');
                searchForm.submit();
            }, 1100);
        });

        // Manejar el evento 'keydown' para permitir Enter sin debounce
        document.getElementById('searchInput').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                clearTimeout(this.searchTimeout);
                this.form.submit();
            }
        });

        // Función para mostrar modal (existente)
        function openModal(itemId) {
            fetch(`/items/${itemId}/details`)
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
                            <!-- ... resto del contenido del modal ... -->
                        </div>
                    `;
                    
                    document.getElementById('itemModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los detalles del ítem');
                });
        }

        // Funciones para cerrar modal (existentes)
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

        function confirmDelete(event) {
            const confirmed = confirm("¿Estás seguro que deseas eliminar este ítem? Esta acción no se puede deshacer.");
            if (!confirmed) {
                event.preventDefault(); // Detiene el envío del formulario
                return false;
            }
            return true;
        }
    </script>
</body>
</html>