<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Generar Reportes - INVEX</title>
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

        <div class="py-6 flex-grow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900 mb-6">Generar Reportes de Inventario</h1>
                    
                    <form id="reportForm" method="POST" action="{{ route('items.generateReport') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                            <!-- Filtro por fechas -->
                            <div>
                                <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Rango de Fechas</label>
                                <select id="date_range" name="date_range" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos los registros</option>
                                    <option value="entry_date">Por fecha de entrada</option>
                                    <option value="modification_date">Por fecha de modificación</option>
                                    <option value="exit_date">Por fecha de salida</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                                <input type="date" id="start_date" name="start_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" disabled>
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                                <input type="date" id="end_date" name="end_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" disabled>
                            </div>
                            
                            <!-- Filtro por estado -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                                <select id="status" name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos los estados</option>
                                    <option value="DISPONIBLE">Disponible</option>
                                    <option value="DETENIDA">Detenida</option>
                                    <option value="TRABAJANDO">Trabajando</option>
                                    <option value="POR SALIR">Por salir</option>
                                    <option value="COMPRAS">Compras</option>
                                </select>
                            </div>
                            
                            <!-- Filtro por usuario -->
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Creado por:</label>
                                <select id="user_id" name="user_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos los usuarios</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->user_id }}">{{ $user->user_name }} {{ $user->user_last_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro por usuario modificador -->
                            <div>
                                <label for="modified_user_id" class="block text-sm font-medium text-gray-700 mb-1">Modificado por:</label>
                                <select id="modified_user_id" name="modified_user_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos los usuarios</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->user_id }}">{{ $user->user_name }} {{ $user->user_last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>
                        
                        <div class="flex flex-wrap gap-3 justify-end">
                            <button type="button" onclick="resetForm()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Limpiar filtros
                            </button>
                            <button type="submit" name="format" value="pdf" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-file-pdf mr-2"></i> Generar PDF
                            </button>
                            <button type="submit" name="format" value="excel" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-file-excel mr-2"></i> Generar Excel
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Sección de Vista Previa -->
                <div id="previewContainer" class="bg-white shadow-sm rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Vista Previa del Reporte</h2>
                    <div id="previewContent" class="text-gray-500 italic">
                        Seleccione filtros para ver una vista previa...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para resetear el formulario
        function resetForm() {
            document.getElementById('reportForm').reset();
            document.getElementById('start_date').disabled = true;
            document.getElementById('end_date').disabled = true;
            loadPreview();
        }
        
        // Manejo de campos de fecha
        document.getElementById('date_range').addEventListener('change', function() {
            const dateTypeSelected = this.value !== '';
            document.getElementById('start_date').disabled = !dateTypeSelected;
            document.getElementById('end_date').disabled = !dateTypeSelected;
            
            
            if (!dateTypeSelected) {
                document.getElementById('start_date').value = '';
                document.getElementById('end_date').value = '';
            }
            
            // Actualizar vista previa si hay otros filtros activos
            checkFiltersAndLoadPreview();
        });
        
        // Inicializar estado de los campos de fecha
        document.addEventListener('DOMContentLoaded', function() {
            const dateTypeSelected = document.getElementById('date_range').value !== '';
            document.getElementById('start_date').disabled = !dateTypeSelected;
            document.getElementById('end_date').disabled = !dateTypeSelected;
            
            // Configurar eventos para todos los filtros
            setupFilterEvents();
            loadPreview();
        });
        
        // Configurar eventos para todos los campos de filtro
        function setupFilterEvents() {
            const filterElements = [
                'date_range', 'start_date', 'end_date', 
                'status', 'user_id', 'modified_user_id', 'location', 'location_value'
            ];
            
            filterElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('change', function() {
                        checkFiltersAndLoadPreview();
                    });
                }
            });
        }
        

        function checkFiltersAndLoadPreview() {
            loadPreview(); // Siempre cargar
        }
        
        // Obtener datos del formulario
        function getFormData() {
            const form = document.getElementById('reportForm');
            const formData = {};
            
            Array.from(form.elements).forEach(element => {
                if (element.name && element.type !== 'button' && element.type !== 'submit') {
                    formData[element.name] = element.value;
                }
            });
            
            return formData;
        }
        
        // Cargar vista previa via AJAX
        function loadPreview() {
            const formData = getFormData();
            const previewContent = document.getElementById('previewContent');
            
            // Mostrar indicador de carga
            previewContent.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin text-indigo-500 text-2xl"></i>
                    <p class="mt-2 text-gray-600">Cargando vista previa...</p>
                </div>
            `;
            
            // Convertir datos a parámetros de URL para GET
            const params = new URLSearchParams();
            for (const key in formData) {
                if (formData[key]) {
                    params.append(key, formData[key]);
                }
            }
            
            // Enviar solicitud (ahora puede ser GET o POST)
            fetch(`{{ route('items.getFilteredItems') }}?${params.toString()}`, {
                method: 'GET', // Cambiado a GET para la paginación
                headers: {
                    'Accept': 'text/html'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Error en la respuesta');
                return response.text();
            })
            .then(html => {
                previewContent.innerHTML = html;
                // Reconfigurar eventos después de actualizar el DOM
                setupPaginationEvents();
            })
            .catch(error => {
                console.error('Error:', error);
                previewContent.innerHTML = `
                    <div class="text-red-500 p-4 rounded bg-red-50">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Error al cargar la vista previa.
                    </div>
                `;
            });
        }

        // Configurar eventos para los links de paginación
        function setupPaginationEvents() {
            const previewContainer = document.getElementById('previewContent');

            previewContainer.querySelectorAll('.pagination a').forEach(link => {
                const clone = link.cloneNode(true);
                link.parentNode.replaceChild(clone, link);
            });

            previewContainer.querySelectorAll('.pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    const url = this.href;

                    previewContainer.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-spinner fa-spin text-indigo-500 text-2xl"></i>
                            <p class="mt-2 text-gray-600">Cargando página...</p>
                        </div>
                    `;

                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error("Error al paginar");
                        return response.text();
                    })
                    .then(html => {
                        previewContainer.innerHTML = html;
                        setupPaginationEvents();
                    })
                    .catch(error => {
                        previewContainer.innerHTML = `
                            <div class="text-red-500 p-4 bg-red-50 rounded">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                No se pudo cargar la nueva página del reporte.
                            </div>
                        `;
                        console.error('Error de paginación AJAX:', error);
                    });
                });
            });
        }

        document.getElementById('userMenuButton').addEventListener('click', function(e) {
        e.preventDefault();
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