<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi RAB Jurusan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { 800: '#1e3a5f', 700: '#254b7a' }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-navy-800 via-blue-900 to-blue-700 flex items-center justify-center font-sans p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <!-- Top branding -->
        <div class="bg-navy-800 px-8 py-8 text-white text-center">
            <!-- Ikon buku SVG -->
            <div class="mx-auto w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mb-4">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <p class="text-blue-200 text-sm font-medium">UIN Alauddin Makassar</p>
            <h1 class="text-xl font-bold mt-1">Sistem Informasi RAB Jurusan</h1>
        </div>

        <!-- Form -->
        <div class="px-8 py-8">
            <h2 class="text-gray-700 font-semibold text-lg mb-6 text-center">Masuk ke Akun Anda</h2>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-5 text-sm">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <strong>Login gagal</strong>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 pl-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-5 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        value="{{ old('email') }}"
                        placeholder="nama@uin-alauddin.ac.id"
                        class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-navy-800 focus:border-navy-800 transition @error('email') border-red-400 bg-red-50 @enderror"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            placeholder="••••••••"
                            class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-navy-800 focus:border-navy-800 transition pr-10 @error('password') border-red-400 bg-red-50 @enderror"
                        >
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIcon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-navy-800 border-gray-300 rounded">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-navy-800 hover:bg-navy-700 text-white font-semibold py-2.5 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-800"
                >
                    Masuk
                </button>
            </form>
        </div>

        <div class="px-8 pb-6 text-center text-xs text-gray-400">
            Jika mengalami masalah login, hubungi administrator sistem.
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    </script>
</body>
</html>
