<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Invex</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Header opcional -->
    {{-- @include('partials.header') --}}
    
    <main class="flex-grow-1">
        <div class="container py-4">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top border-gray-200 mt-auto">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <!-- Marca -->
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="text-xl font-bold text-gray-800">Invex</span>
                        <span class="mx-3 text-gray-300">/</span>
                        <span class="text-gray-600">FC Services</span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Solución integral de gestión</p>
                </div>

                <!-- Derechos + redes -->
                <div class="flex flex-col items-center md:items-end">
                    <div class="flex space-x-4 mb-3">
                        <!-- Iconos de redes sociales -->
                        <a href="#" class="text-gray-400 hover:text-indigo-600 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-indigo-600 transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-indigo-600 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center text-sm">
                        <div class="flex items-center mr-0 sm:mr-4 mb-2 sm:mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span class="text-gray-500">{{ date('Y') }}</span>
                        </div>
                        <span class="text-gray-500 text-sm">Todos los derechos reservados</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>