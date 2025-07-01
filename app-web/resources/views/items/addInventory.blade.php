<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Item - INVEX</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
<body class="bg-gray-50">
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center mr-3 group">
                    <span class="md:hidden text-gray-600 hover:text-gray-900 mr-1">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                    <span class="hidden md:flex items-center text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-home mr-1"></i>
                        <span class="text-sm">Inicio</span>
                    </span>
                </a>
                
                <img class="h-8 w-auto" src="{{ asset('icons/warehouse-stock-svgrepo-com.svg') }}" alt="Logo">
                <span class="ml-2 text-xl font-semibold text-gray-900">INVEX</span>
            </div>
            <div class="flex items-center space-x-4">
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

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">Agregar Nuevo Ítem al Inventario</h1>
                <!-- <h2 class="text-xl font-semibold text-gray-800">Agregar Nuevo Ítem al Inventario</h2> -->
                <p class="mt-1 text-sm text-gray-600">Complete todos los campos requeridos (*) para registrar un nuevo ítem.</p>
            </div>
            
            <form action="{{ route('items.store') }}" method="POST" class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-sm">
    @csrf
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Columna 1 -->
        <div class="space-y-5">
            <!-- Activo Fijo -->
            <div>
                <label for="item_activo_fijo" class="block mb-1 text-sm font-medium text-gray-600">
                    Activo Fijo <span class="text-red-400">*</span>
                </label>
                <input type="text" name="item_activo_fijo" id="item_activo_fijo" 
                    class="w-full px-3 py-2 text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 @error('item_activo_fijo') border-red-400 @enderror"
                    value="{{ old('item_activo_fijo') }}" required>
                @error('item_activo_fijo')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Nombre -->
            <div>
                <label for="item_nombre" class="block mb-1 text-sm font-medium text-gray-600">
                    Nombre del Ítem <span class="text-red-400">*</span>
                </label>
                <input type="text" name="item_nombre" id="item_nombre" 
                    class="w-full px-3 py-2 text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 @error('item_nombre') border-red-400 @enderror"
                    value="{{ old('item_nombre') }}" required>
                @error('item_nombre')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Tag -->
            <div>
                <label for="item_tag" class="block mb-1 text-sm font-medium text-gray-600">
                    Tag <span class="text-red-400">*</span>
                </label>
                <input type="text" name="item_tag" id="item_tag" 
                    class="w-full px-3 py-2 text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 @error('item_tag') border-red-400 @enderror"
                    value="{{ old('item_tag') }}" required>
                @error('item_tag')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Descripción -->
            <div>
                <label for="item_descripcion" class="block mb-1 text-sm font-medium text-gray-600">
                    Descripción <span class="text-red-400">*</span>
                </label>
                <textarea name="item_descripcion" id="item_descripcion" rows="3"
                    class="w-full px-3 py-2 text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 @error('item_descripcion') border-red-400 @enderror"
                    required>{{ old('item_descripcion') }}</textarea>
                @error('item_descripcion')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Columna 2 -->
        <div class="space-y-5">
            <!-- Tamaño -->
            <div>
                <label for="item_size" class="block mb-1 text-sm font-medium text-gray-600">
                    Tamaño/Dimensiones
                </label>
                <input type="text" name="item_size" id="item_size" 
                    class="w-full px-3 py-2 text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 @error('item_size') border-red-400 @enderror"
                    value="{{ old('item_size') }}">
                @error('item_size')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Origen -->
            <div>
                <label for="item_origen" class="block mb-1 text-sm font-medium text-gray-600">
                    Origen (Local procedencia)<span class="text-red-400">*</span>
                </label>
                <input type="text" name="item_origen" id="item_origen" 
                    class="w-full px-3 py-2 text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 @error('item_origen') border-red-400 @enderror"
                    value="{{ old('item_origen') }}" required>
                @error('item_origen')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Destino -->
            <div>
                <label for="item_destino" class="block mb-1 text-sm font-medium text-gray-600">
                    Destino (Local destino)<span class="text-red-400">*</span>
                </label>
                <input type="text" name="item_destino" id="item_destino" 
                    class="w-full px-3 py-2 text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 @error('item_destino') border-red-400 @enderror"
                    value="{{ old('item_destino') }}" required>
                @error('item_destino')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Fecha Entrada -->
            <div>
                <label for="item_fecha_entrada" class="block mb-1 text-sm font-medium text-gray-600">
                    Fecha de Entrada <span class="text-red-400">*</span>
                </label>
                <input type="date" name="item_fecha_entrada" id="item_fecha_entrada" 
                    class="w-full px-3 py-2 text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 @error('item_fecha_entrada') border-red-400 @enderror"
                    value="{{ old('item_fecha_entrada', date('Y-m-d')) }}" required>
                @error('item_fecha_entrada')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Fecha Salida -->
            <div>
                <label for="item_fecha_salida" class="block mb-1 text-sm font-medium text-gray-600">
                    Fecha de Salida
                </label>
                <input type="date" name="item_fecha_salida" id="item_fecha_salida" 
                    class="w-full px-3 py-2 text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 @error('item_fecha_salida') border-red-400 @enderror"
                    value="{{ old('item_fecha_salida') }}">
                @error('item_fecha_salida')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Observaciones -->
    <div class="mt-6">
        <label for="item_observaciones" class="block mb-1 text-sm font-medium text-gray-600">
            Observaciones
        </label>
        <textarea name="item_observaciones" id="item_observaciones" rows="3"
            class="w-full px-3 py-2 text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 @error('item_observaciones') border-red-400 @enderror">{{ old('item_observaciones') }}</textarea>
        @error('item_observaciones')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Estado -->
    <div class="mt-6">
        <label for="item_status" class="block mb-1 text-sm font-medium text-gray-600">
            Status <span class="text-red-400">*</span>
        </label>
        <select name="item_status" id="item_status" 
            class="w-full px-3 py-2 text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 @error('item_status') border-red-400 @enderror" required>
            <option value="">Seleccione un status</option>
            <option value="DISPONIBLE" {{ old('item_status') == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
            <option value="DETENIDA" {{ old('item_status') == 'DETENIDA' ? 'selected' : '' }}>DETENIDA</option>
            <option value="TRABAJANDO" {{ old('item_status') == 'TRABAJANDO' ? 'selected' : '' }}>TRABAJANDO</option>
            <option value="POR SALIR" {{ old('item_status') == 'POR SALIR' ? 'selected' : '' }}>POR SALIR</option>
            <option value="COMPRAS" {{ old('item_status') == 'Compras' ? 'selected' : '' }}>Compras</option>
        </select>
        @error('item_status')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Botones -->
    <div class="mt-8 flex justify-end space-x-3">
        <a href="{{ route('items.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
            Cancelar
        </a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
            Guardar Ítem
        </button>
    </div>
</form>
        </div>
    </main>

    <script>
        // Mostrar/ocultar menú de usuario
        document.getElementById('userMenuButton').addEventListener('click', function() {
            document.getElementById('userMenu').classList.toggle('hidden');
        });

        // Cerrar menú al hacer clic fuera de él
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('userMenu');
            const userMenuButton = document.getElementById('userMenuButton');
            
            if (!userMenu.contains(event.target) && !userMenuButton.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>