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
                <!-- Mostrar mensajes de sesión -->
                @if(session('status'))
                <div class="mb-4 p-3 text-sm text-green-700 bg-green-100 rounded-lg">
                    {{ session('status') }}
                </div>
                @endif

                <form class="space-y-6" id="loginForm" method="POST" action="{{ route('login.form') }}">
                    @csrf

                    <div>
                        <label
                            for="username"
                            class="block text-sm/6 font-medium text-gray-900"
                            >Usuario o Email</label
                        >
                        <div class="mt-2">
                            <input
                                type="text"
                                name="username"
                                id="username"
                                autocomplete="username"
                                required
                                value="{{ old('username') }}"
                                class="block w-full rounded-md border border-black bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                            />
                        </div>
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                        />
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Recordar sesión
                        </label>
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
                        href="{{ route('register.form') }}"
                        class="font-semibold text-indigo-600 hover:text-indigo-500"
                        >Registrate</a
                    >
                </p>
            </div>
        </div>

        <script>
            document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Mostrar spinner
    document.getElementById('spinner').classList.remove('hidden');
    document.getElementById('btnText').classList.add('hidden');
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('errorMessage').classList.add('hidden');
    
    try {
        const response = await fetch("{{ route('login') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                username: document.getElementById('username').value,
                password: document.getElementById('password').value,
                remember: document.getElementById('remember').checked
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || data.message || 'Error en el login');
        }

        // Redirección exitosa
        window.location.href = data.redirect || '/dashboard';

    } catch (error) {
        showError(error.message);
        
        // Recargar el token CSRF si hay error
        await fetch('/csrf-token')
            .then(res => res.json())
            .then(data => {
                document.querySelector('meta[name="csrf-token"]').content = data.token;
            });
    } finally {
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

    @include('partials.footer')
</html>