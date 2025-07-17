<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Restablecer Contraseña</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-6">
                <img class="mx-auto h-16 w-auto" src="{{ asset('icons/warehouse-stock-svgrepo-com.svg') }}" alt="Logo">
                <h2 class="mt-4 text-2xl font-bold text-gray-900">Restablecer Contraseña</h2>
            </div>

            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token ?? old('token') }}">

                <!-- Mostrar errores generales -->
                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required
                        class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                        value="{{ old('email', $email ?? '') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                    <div class="mt-1 relative">
                        <input type="password" id="password" name="password" required
                            class="block w-full rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                            value="{{ old('password') }}"
                            oninput="validatePassword(this.value)"
                            onfocus="showRequirements()"
                            onblur="hideRequirementsIfEmpty()">
                        
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- Mensaje de requisitos -->
                        <div id="passwordRequirements" class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200 {{ old('password') ? '' : 'hidden' }}">
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
                    <label for="password-confirm" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                    <input type="password" id="password-confirm" name="password_confirmation" required
                        class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                        value="{{ old('password_confirmation') }}">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Restablecer Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Mostrar requisitos si hay algún error y ya había contraseña
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            const confirmField = document.getElementById('password-confirm');
            
            if (passwordField.value) {
                validatePassword(passwordField.value);
                document.getElementById('passwordRequirements').classList.remove('hidden');
            }
            
            // Validar coincidencia al cargar si hay valores
            if (passwordField.value && confirmField.value) {
                validatePasswordMatch();
            }
        });

        // Agregar event listeners a los campos de contraseña
        document.getElementById('password').addEventListener('input', function() {
            validatePassword(this.value);
            validatePasswordMatch(); // Validar coincidencia cuando cambia la contraseña principal
        });

        document.getElementById('password-confirm').addEventListener('input', function() {
            validatePasswordMatch(); // Validar coincidencia cuando cambia la confirmación
        });

        function validatePasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password-confirm').value;
            const confirmField = document.getElementById('password-confirm');
            const errorElement = document.getElementById('password-match-error');

            // Solo validar si ambos campos tienen contenido
            if (password && confirmPassword) {
                if (password !== confirmPassword) {
                    confirmField.classList.add('border-red-500');
                    confirmField.classList.remove('border-gray-300');
                    if (!errorElement) {
                        const errorDiv = document.createElement('p');
                        errorDiv.id = 'password-match-error';
                        errorDiv.className = 'mt-1 text-sm text-red-600';
                        errorDiv.textContent = 'Las contraseñas no coinciden';
                        confirmField.parentNode.appendChild(errorDiv);
                    }
                    return false;
                } else {
                    confirmField.classList.remove('border-red-500');
                    confirmField.classList.add('border-gray-300');
                    if (errorElement) {
                        errorElement.remove();
                    }
                    return true;
                }
            }
            return false;
        }

        function showRequirements() {
            document.getElementById('passwordRequirements').classList.remove('hidden');
        }

        function hideRequirementsIfEmpty() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password-confirm').value;
            
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
    </script>
    @include('partials.footer')
</body>
</html>