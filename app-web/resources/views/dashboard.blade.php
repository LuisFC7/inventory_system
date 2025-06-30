<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Barra superior (se mantiene igual) -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                <div class="flex items-center">
                    <img class="h-8 w-auto" src="{{ asset('icons/warehouse-stock-svgrepo-com.svg') }}" alt="Logo">
                    <span class="ml-2 text-xl font-semibold text-gray-900">MiApp</span>
                </div>
                <div class="relative">
                    <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-medium">{{ $initials }}</span>
                        </div>
                        <span class="hidden md:inline text-gray-700">{{ $user->user_name }}</span>
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </button>
                    
                    <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                        <div class="px-4 py-2 border-b">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-600 font-medium">{{ $initials }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $user->user_name }} {{ $user->user_last_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->user_email }}</p>
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
        </header>

        <!-- Contenido principal -->
        <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="bg-white shadow rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900">Bienvenido, {{ $user->user_name }}</h1>
                <p class="mt-2 text-gray-600">
                    Último acceso: 
                    @if($user->user_last_access)
                        {{ $user->user_last_access->locale('es')->diffForHumans(['parts' => 1, 'short' => false]) }}
                    @else
                        Nunca
                    @endif
                </p>
                
                <div class="mt-6 grid grid-cols-1 gap-6">
                    <!-- Sección de Acciones Rápidas (ahora ocupa todo el ancho) -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-green-800 mb-4">Acciones Rápidas</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Botón 1 -->
                            <a href="#" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-all border border-gray-200 hover:border-green-300">
                                <i class="fas fa-plus-circle text-green-600 text-2xl mb-2"></i>
                                <span class="text-sm font-medium text-gray-700 text-center">Agregar Producto</span>
                            </a>
                            
                            <!-- Botón 2 -->
                            <a href="{{ route('items.index') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-all border border-gray-200 hover:border-green-300">
                                <i class="fas fa-search text-blue-600 text-2xl mb-2"></i>
                                <span class="text-sm font-medium text-gray-700 text-center">Consultar Inventario</span>
                            </a>
                            
                            <!-- Botón 3 -->
                            <a href="#" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-all border border-gray-200 hover:border-green-300">
                                <i class="fas fa-minus-circle text-red-600 text-2xl mb-2"></i>
                                <span class="text-sm font-medium text-gray-700 text-center">Eliminar Producto</span>
                            </a>
                            
                            <!-- Botón 4 -->
                            <a href="#" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-all border border-gray-200 hover:border-green-300">
                                <i class="fas fa-file-alt text-yellow-600 text-2xl mb-2"></i>
                                <span class="text-sm font-medium text-gray-700 text-center">Generar Reporte</span>
                            </a>
                        </div>
                    </div>
                </div>
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
    </script>
</body>
</html>