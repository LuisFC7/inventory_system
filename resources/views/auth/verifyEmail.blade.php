<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifica tu Email</title>
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
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">
                Verifica tu dirección de correo electrónico
            </h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            @if (session('resent'))
                <div class="mb-4 p-3 text-sm text-green-700 bg-green-100 rounded-lg">
                    {{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-xs border border-gray-200">
                <p class="text-sm text-gray-600 mb-4">
                    {{ __('Antes de continuar, por favor verifica tu correo electrónico con el enlace que te enviamos.') }}
                </p>
                
                <p class="text-sm text-gray-600 mb-4">
                    {{ __('Si no recibiste el correo') }},
                </p>

                <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Reenviar correo de verificación') }}
                    </button>
                </form>
            </div>

            <!-- <div class="mt-6 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        {{ __('Cerrar sesión') }}
                    </button>
                </form>
            </div> -->
        </div>
    </div>

    @include('partials.footer')
</body>
</html>