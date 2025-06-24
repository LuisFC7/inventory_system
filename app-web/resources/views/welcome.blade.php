<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Iniciar Sesión</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
        <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img
                    class="mx-auto h-20 w-auto"
                    src="{{ asset('icons/warehouse-stock-svgrepo-com.svg') }}"
                    alt="Icon App"
                />
                <h2
                    class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900"
                >
                    Inicia sesión en tu cuenta
                </h2>
            </div>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form class="space-y-6" id="loginForm">
                    <div>
                        <label
                            for="email"
                            class="block text-sm/6 font-medium text-gray-900"
                            >Usuario</label
                        >
                        <div class="mt-2">
                            <input
                                type="email"
                                name="email"
                                id="email"
                                autocomplete="email"
                                required
                                class="block w-full rounded-md border border-black bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                            />
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label
                                for="password"
                                class="block text-sm/6 font-medium text-gray-900"
                                >Contraseña</label
                            >
                            <div class="text-sm">
                                <a
                                    href="#"
                                    class="font-semibold text-indigo-600 hover:text-indigo-500"
                                    >¿Olvidaste tu contraseña?</a
                                >
                            </div>
                        </div>
                        <div class="mt-2">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                autocomplete="current-password"
                                required
                                class="block w-full rounded-md border border-black bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                            />
                        </div>
                    </div>

                    <!-- Mensaje de error -->
                    <div id="errorMessage" class="hidden p-3 text-sm text-red-700 bg-red-100 rounded-lg"></div>

                    <div>
                        <button
                            type="submit"
                            id="submitBtn"
                            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        >
                            <span id="btnText">Iniciar Sesión</span>
                            <span id="spinner" class="hidden ml-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>

                <p class="mt-10 text-center text-sm/6 text-gray-500">
                    ¿No tienes una cuenta?, 
                    <a
                        href="#"
                        class="font-semibold text-indigo-600 hover:text-indigo-500"
                        >Registrate</a
                    >
                </p>
            </div>
        </div>

        <script>
            document.getElementById('loginForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Mostrar spinner y deshabilitar botón
                document.getElementById('spinner').classList.remove('hidden');
                document.getElementById('btnText').classList.add('hidden');
                document.getElementById('submitBtn').disabled = true;
                
                // Ocultar mensajes de error previos
                document.getElementById('errorMessage').classList.add('hidden');
                
                const form = e.target;
                const formData = {
                    email: form.email.value,
                    password: form.password.value,
                    _token: '{{ csrf_token() }}' // Solo necesario si no usas el header
                };

                try {
                    const response = await fetch('{{ route("login") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(formData)
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Error en el servidor');
                    }

                    if (data.success) {
                        window.location.href = data.redirect || '/dashboard';
                    } else {
                        showError(data.message || 'Credenciales incorrectas');
                    }
                } catch (error) {
                    showError(error.message || 'Ocurrió un error al iniciar sesión');
                } finally {
                    // Ocultar spinner y habilitar botón
                    document.getElementById('spinner').classList.add('hidden');
                    document.getElementById('btnText').classList.remove('hidden');
                    document.getElementById('submitBtn').disabled = false;
                }
            });

            function showError(message) {
                const errorElement = document.getElementById('errorMessage');
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
                
                // Hacer scroll al mensaje de error
                errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        </script>
    </body>
</html>