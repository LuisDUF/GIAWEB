<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles

        <script>
                window.tailwindConfig = {
                    theme: {
                        extend: {
                            colors: {
                                brand: '{{ env("VITE_GIA_PRIMARY", "#222831") }}',
                            }
                        }
                    }
                }
            </script>
                    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    </head>
    <body>
        @yield('content')

        @livewireScripts
    </body>
</html>
