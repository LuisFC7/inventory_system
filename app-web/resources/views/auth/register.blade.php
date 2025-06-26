<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-lg shadow-sm p-8">
            <div class="text-center mb-8">
                <img
                    class="mx-auto h-20 w-auto"
                    src="{{ asset('icons/warehouse-stock-svgrepo-com.svg') }}"
                    alt="Icon App"
                />
                <h1 class="text-2xl font-light text-gray-800">Crear una cuenta</h1>
            </div>

            <!-- Mensajes de éxito/error -->
            <div class="mb-4 space-y-2">
                @if(session('success'))
                    <div x-data="{ show: true }" 
                        x-show="show"
                        x-init="@if(session('autoRedirect')) setTimeout(() => window.location.href = '/', 2000); @endif"
                        class="p-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-3 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>Error en el registro:</span>
                        </div>
                        <ul class="mt-1 ml-6 list-disc text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" id="name" name="name" required
                            value = "{{ old('name') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="lastname" class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                        <input type="text" id="lastname" name="lastname" required
                            value = "{{ old('lastname') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                    <input type="email" id="email" name="email" required
                        value = "{{ old('email') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Nombre de usuario</label>
                    <input type="text" id="username" name="username" required
                        value = "{{ old('username') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                    <input type="text" id="department" name="department" required
                        value = "{{ old('department') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" id="password" name="password" required
                        value = "{{ old('password') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        value = "{{ old('password_confirmation') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="pt-2">
                    <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                        Registrarse
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    ¿Ya tienes una cuenta? 
                    <a href="/" class="text-blue-600 hover:text-blue-800">Inicia sesión</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>