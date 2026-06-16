<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI RAB Jurusan - UIN Alauddin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e3a5f',
                        secondary: '#2563eb',
                        success: '#16a34a',
                        warning: '#d97706',
                        danger: '#dc2626',
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar -->
        <div class="bg-primary text-white w-full md:w-64 flex-shrink-0 flex flex-col">
            <div class="p-5 flex items-center justify-between border-b border-blue-900">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-graduation-cap text-2xl text-blue-400"></i>
                    <div>
                        <span class="font-bold text-lg block">SI RAB Jurusan</span>
                        <span class="text-xs text-blue-300">UIN Alauddin Makassar</span>
                    </div>
                </div>
            </div>
            
            <nav class="flex-grow p-4 space-y-2">
                @auth
                    @php $role = auth()->user()->role; @endphp
                    
                    @if($role === 'pengusul')
                        <a href="{{ route('pengusul.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg hover:bg-blue-900 transition-colors {{ Route::is('pengusul.dashboard') ? 'bg-blue-900' : '' }}">
                            <i class="fa-solid fa-chart-line w-5"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('pengusul.rab.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg hover:bg-blue-900 transition-colors {{ Route::is('pengusul.rab.index') ? 'bg-blue-900' : '' }}">
                            <i class="fa-solid fa-file-invoice-dollar w-5"></i>
                            <span>RAB Saya</span>
                        </a>
                        <a href="{{ route('pengusul.rab.create') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg hover:bg-blue-900 transition-colors {{ Route::is('pengusul.rab.create') ? 'bg-blue-900' : '' }}">
                            <i class="fa-solid fa-circle-plus w-5"></i>
                            <span>Ajukan RAB Baru</span>
                        </a>
                    @elseif($role === 'kaprodi')
                        <a href="{{ route('kaprodi.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg hover:bg-blue-900 transition-colors {{ Route::is('kaprodi.dashboard') ? 'bg-blue-900' : '' }}">
                            <i class="fa-solid fa-chart-line w-5"></i>
                            <span>Dashboard / Verifikasi</span>
                        </a>
                    @elseif($role === 'wd_keuangan')
                        <a href="{{ route('wd.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg hover:bg-blue-900 transition-colors {{ Route::is('wd.dashboard') ? 'bg-blue-900' : '' }}">
                            <i class="fa-solid fa-chart-line w-5"></i>
                            <span>Dashboard / Verifikasi</span>
                        </a>
                    @elseif($role === 'dekan')
                        <a href="{{ route('dekan.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg hover:bg-blue-900 transition-colors {{ Route::is('dekan.dashboard') ? 'bg-blue-900' : '' }}">
                            <i class="fa-solid fa-chart-line w-5"></i>
                            <span>Dashboard / Persetujuan</span>
                        </a>
                    @elseif($role === 'tata_usaha')
                        <a href="{{ route('tu.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg hover:bg-blue-900 transition-colors {{ Route::is('tu.dashboard') ? 'bg-blue-900' : '' }}">
                            <i class="fa-solid fa-chart-line w-5"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('tu.laporan.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg hover:bg-blue-900 transition-colors {{ Route::is('tu.laporan.index') ? 'bg-blue-900' : '' }}">
                            <i class="fa-solid fa-file-pdf w-5"></i>
                            <span>Laporan Bulanan</span>
                        </a>
                        <a href="{{ route('tu.aset.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg hover:bg-blue-900 transition-colors {{ Route::is('tu.aset.index') ? 'bg-blue-900' : '' }}">
                            <i class="fa-solid fa-boxes-stacked w-5"></i>
                            <span>Modul Aset</span>
                        </a>
                    @endif
                    
                    <a href="{{ route('notifications.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg hover:bg-blue-900 transition-colors {{ Route::is('notifications.index') ? 'bg-blue-900' : '' }}">
                        <i class="fa-solid fa-bell w-5"></i>
                        <span>Notifikasi</span>
                        @if(auth()->user()->unreadNotificationsCount() > 0)
                            <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full ml-auto">{{ auth()->user()->unreadNotificationsCount() }}</span>
                        @endif
                    </a>
                @endauth
            </nav>

            <div class="p-4 border-t border-blue-900">
                @auth
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-8 h-8 rounded-full bg-blue-800 flex items-center justify-center font-bold text-sm text-blue-300">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="truncate">
                            <span class="font-semibold block text-sm">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-blue-300 capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center space-x-3 px-4 py-2 rounded-lg text-blue-300 hover:text-white hover:bg-red-800 transition-colors">
                            <i class="fa-solid fa-right-from-bracket w-5"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                @endauth
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow flex flex-col overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between border-b">
                <h1 class="text-xl font-bold text-gray-800">
                    @yield('header_title', 'Sistem Informasi RAB')
                </h1>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- Notification bell -->
                        <div class="relative">
                            <a href="{{ route('notifications.index') }}" class="text-gray-500 hover:text-primary transition-colors">
                                <i class="fa-solid fa-bell text-xl"></i>
                                @if(auth()->user()->unreadNotificationsCount() > 0)
                                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border border-white"></span>
                                @endif
                            </a>
                        </div>
                        
                        <div class="text-right hidden sm:block">
                            <span class="font-semibold block text-sm text-gray-800">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-gray-500 font-medium capitalize">{{ str_replace('_', ' ', auth()->user()->role) }} @if(auth()->user()->unit) ({{ auth()->user()->unit }}) @endif</span>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- Content Area -->
            <main class="p-6 max-w-7xl w-full mx-auto">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border-l-4 border-success text-green-700 p-4 rounded shadow-sm flex items-center justify-between" role="alert">
                        <div class="flex items-center">
                            <i class="fa-solid fa-circle-check mr-3 text-lg text-success"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-950 font-bold">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border-l-4 border-danger text-danger p-4 rounded shadow-sm flex items-center justify-between" role="alert">
                        <div class="flex items-center">
                            <i class="fa-solid fa-circle-exclamation mr-3 text-lg text-danger"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-danger hover:text-red-950 font-bold">&times;</button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
