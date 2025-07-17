<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @keyframes checkmark {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        .password-valid::before {
            content: "✓ ";
            display: inline;
            animation: checkmark 0.3s ease;
        }
        
        .password-invalid::before {
            content: "✗ ";
            display: inline;
        }
        
        .requirement-item {
            transition: all 0.3s ease;
        }
        
        /* Animación para el mensaje de error */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .password-match-error {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
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
                            value="{{ old('name') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="lastname" class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                        <input type="text" id="lastname" name="lastname" required
                            value="{{ old('lastname') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                    <input type="email" id="email" name="email" required
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Nombre de usuario</label>
                    <input type="text" id="username" name="username" required
                        value="{{ old('username') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                    <input type="text" id="department" name="department" required
                        value="{{ old('department') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            oninput="validatePassword(this.value)"
                            onfocus="showRequirements()"
                            onblur="hideRequirementsIfEmpty()">
                        
                        <!-- Mensaje de requisitos -->
                        <div id="passwordRequirements" class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200 hidden">
                            <p class="text-sm font-medium text-gray-800 mb-1">La contraseña debe contener:</p>
                            <ul class="space-y-1">
                                <li id="reqLength" class="requirement-item text-xs text-gray-500 password-invalid">Al menos 8 caracteres</li>
                                <li id="reqUpper" class="requirement-item text-xs text-gray-500 password-invalid">Una letra mayúscula (A-Z)</li>
                                <li id="reqLower" class="requirement-item text-xs text-gray-500 password-invalid">Una letra minúscula (a-z)</li>
                                <li id="reqNumber" class="requirement-item text-xs text-gray-500 password-invalid">Un número (0-9)</li>
                                <li id="reqSpecial" class="requirement-item text-xs text-gray-500 password-invalid">Un carácter especial (@$!%*?&)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        oninput="validatePasswordMatch()">
                    <div id="passwordMatchError" class="hidden mt-1 text-sm text-red-600 password-match-error">
                        Las contraseñas no coinciden
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" id="submitBtn"
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

    <script>
        // Mostrar requisitos si hay algún error y ya había contraseña
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            if (passwordField.value) {
                validatePassword(passwordField.value);
                document.getElementById('passwordRequirements').classList.remove('hidden');
            }
            
            // Validar coincidencia al cargar si hay valores
            if (passwordField.value && document.getElementById('password_confirmation').value) {
                validatePasswordMatch();
            }
        });

        function showRequirements() {
            document.getElementById('passwordRequirements').classList.remove('hidden');
        }

        function hideRequirementsIfEmpty() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password === '' && confirmPassword === '') {
                document.getElementById('passwordRequirements').classList.add('hidden');
            }
        }

        function validatePassword(password) {
            const requirements = document.getElementById('passwordRequirements');
            requirements.classList.remove('hidden');
            
            // Validar cada requisito
            const hasMinLength = password.length >= 8;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[@$!%*?&]/.test(password);
            
            // Actualizar visualización de requisitos
            updateRequirement('reqLength', hasMinLength);
            updateRequirement('reqUpper', hasUpper);
            updateRequirement('reqLower', hasLower);
            updateRequirement('reqNumber', hasNumber);
            updateRequirement('reqSpecial', hasSpecial);
            
            // Validar también la coincidencia
            validatePasswordMatch();
        }

        function validatePasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const confirmField = document.getElementById('password_confirmation');
            const errorElement = document.getElementById('passwordMatchError');
            
            // Solo validar si ambos campos tienen contenido
            if (password && confirmPassword) {
                if (password !== confirmPassword) {
                    confirmField.classList.add('border-red-500');
                    confirmField.classList.remove('border-gray-300');
                    errorElement.classList.remove('hidden');
                    return false;
                } else {
                    confirmField.classList.remove('border-red-500');
                    confirmField.classList.add('border-gray-300');
                    errorElement.classList.add('hidden');
                    return true;
                }
            }
            return false;
        }

        function updateRequirement(elementId, isValid) {
            const element = document.getElementById(elementId);
            if (isValid) {
                element.classList.remove('password-invalid', 'text-gray-500');
                element.classList.add('password-valid', 'text-green-600');
            } else {
                element.classList.remove('password-valid', 'text-green-600');
                element.classList.add('password-invalid', 'text-gray-500');
            }
        }

        // Validar antes de enviar el formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                validatePasswordMatch();
                document.getElementById('passwordMatchError').classList.remove('hidden');
                document.getElementById('password_confirmation').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });
    </script>
    @include('partials.footer')
</body>
</html>