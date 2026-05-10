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
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-surface-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-surface-200">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-8 h-8 premium-gradient rounded-lg flex items-center justify-center text-white">
                            <i data-lucide="briefcase" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-primary-950">{{ Auth::user()->business_name ?? 'GO Business' }}</span>
                    </a>
                    <button id="close-sidebar" class="lg:hidden p-2 rounded-md hover:bg-surface-100">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <a href="/dashboard" class="flex items-center px-4 py-2 text-surface-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-colors {{ request()->is('dashboard') ? 'bg-primary-50 text-primary-600' : '' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="/transactions" class="flex items-center px-4 py-2 text-surface-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-colors {{ request()->is('transactions*') ? 'bg-primary-50 text-primary-600' : '' }}">
                        <i data-lucide="shopping-cart" class="w-5 h-5 mr-3"></i>
                        <span class="font-medium">Transaksi</span>
                    </a>
                    <a href="/stock" class="flex items-center px-4 py-2 text-surface-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-colors {{ request()->is('stock*') ? 'bg-primary-50 text-primary-600' : '' }}">
                        <i data-lucide="package" class="w-5 h-5 mr-3"></i>
                        <span class="font-medium">Stok Produk</span>
                    </a>
                    <a href="/reports" class="flex items-center px-4 py-2 text-surface-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-colors {{ request()->is('reports*') ? 'bg-primary-50 text-primary-600' : '' }}">
                        <i data-lucide="file-text" class="w-5 h-5 mr-3"></i>
                        <span class="font-medium">Laporan</span>
                    </a>
                    <a href="/analytics" class="flex items-center px-4 py-2 text-surface-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-colors {{ request()->is('analytics*') ? 'bg-primary-50 text-primary-600' : '' }}">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                        <span class="font-medium">Analitik</span>
                    </a>
                </nav>

                <!-- User Profile -->
                <div class="mt-auto p-4 border-t border-surface-100">
                    <div class="flex items-center p-3 bg-surface-50 rounded-2xl">
                        <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center text-white font-bold text-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}{{ substr(strrchr(Auth::user()->name, " "), 1, 1) ?: '' }}
                        </div>
                        <div class="ml-3 min-w-0">
                            <p class="text-sm font-bold text-surface-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-surface-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 lg:ml-64">
            <!-- Topbar -->
            <header class="sticky top-0 z-40 bg-white border-b border-surface-200 lg:px-8 px-4 h-16 flex items-center justify-between">
                <div class="flex items-center">
                    <button id="open-sidebar" class="lg:hidden p-2 rounded-md hover:bg-surface-100 mr-4">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <h1 class="text-lg font-semibold text-surface-900">@yield('title', 'Dashboard')</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button id="profile-btn" class="flex items-center space-x-2 p-1 rounded-full hover:bg-surface-100 transition-colors">
                            <div class="w-8 h-8 rounded-full premium-gradient flex items-center justify-center text-white text-xs font-bold">
                                {{ substr(Auth::user()->name, 0, 1) }}{{ substr(strrchr(Auth::user()->name, " "), 1, 1) ?: '' }}
                            </div>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-surface-500"></i>
                        </button>
                        
                        <div id="profile-dropdown" class="absolute right-0 mt-2 w-56 bg-white border border-surface-200 rounded-2xl shadow-xl py-2 hidden animate-in fade-in slide-in-from-top-2 z-50">
                            <div class="px-4 py-3 border-b border-surface-100 mb-1">
                                <p class="text-xs font-bold text-surface-400 uppercase tracking-widest mb-1">Akun Terhubung</p>
                                <p class="text-sm font-bold text-surface-900 truncate">{{ Auth::user()->name }}</p>
                            </div>
                            <a href="{{ route('profile.index') }}" class="flex items-center px-4 py-2.5 text-sm text-surface-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                <div class="w-8 h-8 bg-surface-50 rounded-lg flex items-center justify-center mr-3 text-surface-500 group-hover:bg-white">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <span class="font-medium">Profil Bisnis</span>
                            </a>
                            <a href="{{ route('profile.settings') }}" class="flex items-center px-4 py-2.5 text-sm text-surface-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                <div class="w-8 h-8 bg-surface-50 rounded-lg flex items-center justify-center mr-3 text-surface-500">
                                    <i data-lucide="settings" class="w-4 h-4"></i>
                                </div>
                                <span class="font-medium">Pengaturan</span>
                            </a>
                            <hr class="my-2 border-surface-100">
                            <form method="POST" action="/logout" class="px-2">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                    <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center mr-3 text-red-500">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                    </div>
                                    <span class="font-bold">Keluar Aplikasi</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 lg:px-8 px-4 py-8 max-w-7xl mx-auto w-full">
                @yield('content')
            </div>
        </main>

        <!-- Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-surface-900/50 z-40 lg:hidden hidden"></div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Elements
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');
        
        const profileBtn = document.getElementById('profile-btn');
        const profileDropdown = document.getElementById('profile-dropdown');

        // Sidebar Toggle Logic
        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        // Dropdown Toggle Logic
        function toggleDropdown(dropdown) {
            dropdown.classList.toggle('hidden');
        }

        // Event Listeners
        openBtn?.addEventListener('click', (e) => { e.stopPropagation(); toggleSidebar(); });
        closeBtn?.addEventListener('click', toggleSidebar);
        overlay?.addEventListener('click', toggleSidebar);

        profileBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown(profileDropdown);
        });

        // Close dropdowns when clicking outside
        window.addEventListener('click', () => {
            profileDropdown?.classList.add('hidden');
        });

        // Prevent dropdown from closing when clicking inside
        profileDropdown?.addEventListener('click', (e) => e.stopPropagation());

        // Auto-hide alerts after 2 seconds
        document.addEventListener('DOMContentLoaded', () => {
            const alerts = document.querySelectorAll('.alert-auto-hide');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 2000);
            });
        });
    </script>
</body>
</html>
