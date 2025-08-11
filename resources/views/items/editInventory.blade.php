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
        <main class="flex-grow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <!-- Encabezado del formulario -->
                    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Actualizar Item</h2>
                                <p class="mt-1 text-sm text-gray-600">Modifica los campos necesarios del item seleccionado</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('items.index') }}" class="flex items-center px-3 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-md transition-colors">
                                    <i class="fas fa-arrow-left mr-2"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de actualización -->
                    <form action="{{ route('items.update', $item->item_id) }}" method="POST" class="px-6 py-5">
                        @csrf
                        @method('PUT')

                        <!-- Mensajes de error/éxito -->
                        @if($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                                <p class="font-medium">Por favor corrige los siguientes errores:</p>
                                <ul class="mt-1 list-disc list-inside text-sm">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
                                <p class="font-medium">{{ session('success') }}</p>
                            </div>
                        @endif

                        <!-- Grid de campos -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Columna izquierda -->
                            <div class="space-y-4">
                                <!-- Activo Fijo -->
                                <div>
                                    <label for="item_activo_fijo" class="block text-sm font-medium text-gray-700">Activo Fijo *</label>
                                    <input type="text" name="item_activo_fijo" id="item_activo_fijo" 
                                           value="{{ old('item_activo_fijo', $item->item_activo_fijo) }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <!-- Nombre -->
                                <div>
                                    <label for="item_nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                                    <input type="text" name="item_nombre" id="item_nombre" 
                                           value="{{ old('item_nombre', $item->item_nombre) }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <!-- Tag -->
                                <div>
                                    <label for="item_tag" class="block text-sm font-medium text-gray-700">Tag</label>
                                    <input type="text" name="item_tag" id="item_tag" 
                                           value="{{ old('item_tag', $item->item_tag) }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <!-- Descripción -->
                                <div>
                                    <label for="item_descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                                    <textarea name="item_descripcion" id="item_descripcion" rows="3"
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('item_descripcion', $item->item_descripcion) }}</textarea>
                                </div>
                            </div>

                            <!-- Columna derecha -->
                            <div class="space-y-4">
                                <!-- Tamaño -->
                                <div>
                                    <label for="item_size" class="block text-sm font-medium text-gray-700">Tamaño</label>
                                    <input type="text" name="item_size" id="item_size" 
                                           value="{{ old('item_size', $item->item_size) }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <!-- Origen -->
                                <div>
                                    <label for="item_origen" class="block text-sm font-medium text-gray-700">Origen *</label>
                                    <input type="text" name="item_origen" id="item_origen" 
                                           value="{{ old('item_origen', $item->item_origen) }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <!-- Destino -->
                                <div>
                                    <label for="item_destino" class="block text-sm font-medium text-gray-700">Destino *</label>
                                    <input type="text" name="item_destino" id="item_destino" 
                                           value="{{ old('item_destino', $item->item_destino) }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <!-- Fechas -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Fecha de Entrada -->
                                    <div>
                                        <label for="item_fecha_entrada" class="block text-sm font-medium text-gray-700">Fecha Entrada *</label>
                                        <input type="date" name="item_fecha_entrada" id="item_fecha_entrada" 
                                            value="{{ old('item_fecha_entrada', $item->item_fecha_entrada ? $item->item_fecha_entrada->format('Y-m-d') : '') }}"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>

                                    <!-- Fecha de Salida -->
                                    <div>
                                        <label for="item_fecha_salida" class="block text-sm font-medium text-gray-700">Fecha Salida</label>
                                        <input type="date" name="item_fecha_salida" id="item_fecha_salida" 
                                            value="{{ old('item_fecha_salida', $item->item_fecha_salida ? $item->item_fecha_salida->format('Y-m-d') : '') }}"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="mt-4">
                                    <label for="item_status" class="block mb-1 text-sm font-medium text-gray-600">
                                        Estado <span class="text-red-400">*</span>
                                    </label>
                                    <select name="item_status" id="item_status" 
                                        class="w-full px-3 py-2 text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 @error('item_status') border-red-400 @enderror" required>
                                        <option value="">Seleccione un estado</option>
                                        @foreach(['DISPONIBLE', 'DETENIDA', 'TRABAJANDO', 'POR SALIR', 'COMPRAS'] as $status)
                                            <option value="{{ $status }}" {{ old('item_status', $item->item_status) == $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('item_status')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones (full width) -->
                        <div class="mt-6">
                            <label for="item_observaciones" class="block text-sm font-medium text-gray-700">Observaciones</label>
                            <textarea name="item_observaciones" id="item_observaciones" rows="3"
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('item_observaciones', $item->item_observaciones) }}</textarea>
                        </div>

                        <!-- Botones de acción -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('items.indexUpdateForm') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-save mr-2"></i> Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Script para el menú de usuario -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButton = document.getElementById('userMenuButton');
            const userMenu = document.getElementById('userMenu');

            userMenuButton.addEventListener('click', function() {
                userMenu.classList.toggle('hidden');
            });

            // Cerrar menú al hacer clic fuera de él
            document.addEventListener('click', function(event) {
                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>