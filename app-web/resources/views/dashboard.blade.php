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
        <!-- Barra superior -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                <div class="flex items-center">
                    <img class="h-8 w-auto" src="{{ asset('icons/warehouse-stock-svgrepo-com.svg') }}" alt="Logo">
                    <span class="ml-2 text-xl font-semibold text-gray-900">MiApp</span>
                </div>
                <div class="relative">
                    <!-- Botón para menú móvil -->
                    <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                            <!-- Avatar placeholder - reemplazar con imagen real -->
                            <span class="text-indigo-600 font-medium" id="userInitials">US</span>
                        </div>
                        <span class="hidden md:inline text-gray-700">Usuario Sistema</span>
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </button>
                    
                    <!-- Menú desplegable -->
                    <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                        <div class="px-4 py-2 border-b">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-600 font-medium" id="userInitialsMenu">US</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900" id="userNameMenu">Usuario Sistema</p>
                                    <p class="text-xs text-gray-500" id="userEmailMenu">usuario@example.com</p>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog mr-2 text-gray-400"></i> Configuración
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-boxes mr-2 text-gray-400"></i> Inventario
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-ellipsis-h mr-2 text-gray-400"></i> Otros
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
            <div class="bg-white shadow rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900">Bienvenido al Panel</h1>
                <p class="mt-2 text-gray-600">Selecciona una opción del menú de usuario para comenzar.</p>
                
                <!-- Versión móvil - Menú como cards -->
                <div class="mt-8 md:hidden grid grid-cols-1 gap-4">
                    <a href="#" class="p-4 border rounded-lg hover:bg-gray-50">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-medium text-gray-900">Configuración</h3>
                                <p class="text-sm text-gray-500">Ajustes de tu cuenta</p>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="p-4 border rounded-lg hover:bg-gray-50">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-50 text-green-600">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-medium text-gray-900">Inventario</h3>
                                <p class="text-sm text-gray-500">Gestiona tus productos</p>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="p-4 border rounded-lg hover:bg-gray-50">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-50 text-yellow-600">
                                <i class="fas fa-ellipsis-h"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-medium text-gray-900">Otros</h3>
                                <p class="text-sm text-gray-500">Otras opciones</p>
                            </div>
                        </div>
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left p-4 border rounded-lg hover:bg-gray-50">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-red-50 text-red-600">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium text-gray-900">Cerrar sesión</h3>
                                    <p class="text-sm text-gray-500">Salir del sistema</p>
                                </div>
                            </div>
                        </button>
                    </form>
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
            
            if (!menu.contains(event.target) && !button.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // Aquí puedes agregar la lógica para cargar los datos del usuario
        // cuando tengas el controlador listo:
        /*
        fetch('/api/user-data')
            .then(response => response.json())
            .then(data => {
                document.getElementById('userInitials').textContent = data.initials;
                document.getElementById('userInitialsMenu').textContent = data.initials;
                document.getElementById('userNameMenu').textContent = data.name;
                document.getElementById('userEmailMenu').textContent = data.email;
                // Actualizar otros elementos según sea necesario
            });
        */
    </script>
</body>
</html>