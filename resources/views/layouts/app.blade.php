<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Informasi RAB Jurusan') - SIRAB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            800: '#1e3a5f',
                            700: '#254b7a',
                            600: '#2c5c95',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-navy-800 text-white flex-shrink-0 hidden md:flex md:flex-col">
            <div class="p-4 border-b border-navy-700">
                <h1 class="text-lg font-bold">SIRAB</h1>
                <p class="text-xs text-gray-300">Sistem Informasi RAB Jurusan</p>
            </div>
            <nav class="flex-1 overflow-y-auto p-3 space-y-1">
                @php
                    $role = auth()->user()->role;
                    $menuItems = [];
                    if ($role === 'pengusul') {
                        $menuItems = [
                            ['label' => 'Dashboard', 'route' => 'pengusul.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['label' => 'RAB Saya', 'route' => 'pengusul.rab.index', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ['label' => 'Ajukan RAB Baru', 'route' => 'pengusul.rab.create', 'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6'],
                        ];
                    } elseif ($role === 'kaprodi') {
                        $menuItems = [
                            ['label' => 'Dashboard', 'route' => 'kaprodi.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['label' => 'RAB Masuk (Pending)', 'route' => 'kaprodi.rab.index', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                        ];
                    } elseif ($role === 'wd_keuangan') {
                        $menuItems = [
                            ['label' => 'Dashboard', 'route' => 'wd.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['label' => 'RAB untuk Verifikasi', 'route' => 'wd.rab.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                        ];
                    } elseif ($role === 'dekan') {
                        $menuItems = [
                            ['label' => 'Dashboard', 'route' => 'dekan.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['label' => 'RAB untuk Persetujuan', 'route' => 'dekan.rab.index', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ];
                    } elseif ($role === 'tata_usaha') {
                        $menuItems = [
                            ['label' => 'Dashboard', 'route' => 'tu.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['label' => 'Laporan', 'route' => 'tu.laporan.index', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ['label' => 'Daftar Aset', 'route' => 'tu.aset.index', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                        ];
                    }
                @endphp
                @foreach ($menuItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 hover:bg-navy-700 {{ request()->routeIs($item['route'] . '*') ? 'bg-navy-700 text-white' : 'text-gray-300' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
            <div class="p-3 border-t border-navy-700">
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>UIN Alauddin Makassar</span>
                </div>
            </div>
        </aside>

        <!-- Mobile sidebar toggle (hidden by default, shown on mobile) -->
        <div class="md:hidden fixed top-0 left-0 z-50 p-2">
            <button id="sidebarToggle" class="text-navy-800 bg-white rounded-lg p-2 shadow">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Main content area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 shadow-sm">
                <div class="px-6 py-3 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
                    <div class="flex items-center gap-4">
                        <!-- Notif Bell -->
                        <a href="{{ route('notifications.index') }}" class="relative text-gray-500 hover:text-gray-700 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @php
                                $unreadCount = auth()->user()->inAppNotifications()->where('is_read', false)->count() ?? 0;
                            @endphp
                            @if ($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                            @endif
                        </a>
                        <!-- User info -->
                        <div class="flex items-center gap-2">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-navy-800 text-white">{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</span>
                            </div>
                            <div class="w-9 h-9 rounded-full bg-navy-800 text-white flex items-center justify-center text-sm font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </div>
                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Flash messages -->
            <div class="px-6 pt-4">
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm mb-4 flex items-start gap-3" role="alert">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                        <button onclick="this.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm mb-4 flex items-start gap-3" role="alert">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                        <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Main content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 px-6 py-3 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} Sistem Informasi RAB Jurusan - UIN Alauddin Makassar
            </footer>
        </div>
    </div>

    <!-- Mobile sidebar overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>
    <div id="mobileSidebar" class="fixed top-0 left-0 z-50 h-full w-64 bg-navy-800 text-white transform -translate-x-full transition-transform duration-300 md:hidden">
        <div class="flex items-center justify-between p-4 border-b border-navy-700">
            <h1 class="text-lg font-bold">SIRAB</h1>
            <button id="sidebarClose" class="text-gray-300 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <nav class="p-3 space-y-1">
            @foreach ($menuItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 hover:bg-navy-700 {{ request()->routeIs($item['route'] . '*') ? 'bg-navy-700 text-white' : 'text-gray-300' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('sidebarToggle');
            const close = document.getElementById('sidebarClose');
            const overlay = document.getElementById('sidebarOverlay');
            const mobileSidebar = document.getElementById('mobileSidebar');

            if (toggle && mobileSidebar && overlay) {
                toggle.addEventListener('click', function() {
                    mobileSidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                });
                const hideSidebar = function() {
                    mobileSidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                };
                if (close) close.addEventListener('click', hideSidebar);
                overlay.addEventListener('click', hideSidebar);
            }

            // Auto-dismiss flash messages after 5 seconds
            document.querySelectorAll('[role="alert"]').forEach(function(el) {
                setTimeout(function() { el.remove(); }, 5000);
            });
        });
    </script>
</body>
</html>
