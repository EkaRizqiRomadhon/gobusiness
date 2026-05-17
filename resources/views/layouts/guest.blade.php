<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'GO Business') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-surface-50 text-surface-900 font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <div class="mb-8 flex items-center space-x-3">
            <div class="w-12 h-12 premium-gradient rounded-xl flex items-center justify-center text-white shadow-lg">
                <i data-lucide="briefcase" class="w-7 h-7"></i>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-primary-950">GO Business</h1>
        </div>

        <div class="w-full @yield('content-width', 'max-w-md')">
            @yield('content')
        </div>

        <footer class="mt-8 text-surface-500 text-sm">
            &copy; {{ date('Y') }} GO Business. Memberdayakan UMKM Indonesia.
        </footer>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
