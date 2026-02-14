<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
        {{-- <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" /> --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-slate-200 dark:bg-slate-800">
        <livewire:partials.navbar />
        <main class="" >
            {{ $slot }}
        </main>
        <livewire:partials.footer />
        @livewireScripts
        {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
        {{-- @livewireAlertScripts --}}

        {{-- <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script> --}}
        {{-- <script data-navigate-once>
            document.addEventListener('livewire:navigated', () => {
                // Initialize Flowbite components after navigation
                setTimeout(() => {
                    initFlowbite();
                }, 100);
            });
        </script> --}}
        {{-- <script>
            const darkToggleBtn = document.getElementById('darkToggle');

            // Apply dark mode based on saved preference or system preference
            if (localStorage.getItem('theme') === 'dark' ||
                (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Toggle dark mode (if button exists on page)
            if (darkToggleBtn) {
                darkToggleBtn.addEventListener('click', () => {
                    document.documentElement.classList.toggle('dark');
                    const isDark = document.documentElement.classList.contains('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                });
            }
        </script> --}}
    </body>
</html>
