<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — PNJ PRIME</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md space-y-6">

        {{-- ─── Logo & Header ──────────────────────────────────────────────── --}}
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-lg mb-4">
                <span class="text-blue-800 font-extrabold text-xl">PNJ</span>
            </div>
            <h1 class="text-3xl font-bold text-white">PNJ PRIME</h1>
            <p class="text-blue-200 text-sm mt-1">Sistem E-Commerce &amp; Booking Jasa Kampus</p>
        </div>

        {{-- ─── Login Card ──────────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Masuk ke Akun</h2>

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-700 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@pnj.ac.id"
                        required
                        autofocus
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                    >
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-blue-800 hover:bg-blue-900 text-white font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200 text-sm"
                >
                    Masuk
                </button>
            </form>
        </div>

        {{-- ─── Demo Accounts ───────────────────────────────────────────────── --}}
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/20">
            <h3 class="text-white font-semibold text-sm mb-3 flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                Akun Demo
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-white/90">
                    <thead>
                        <tr class="border-b border-white/20">
                            <th class="text-left py-1.5 pr-4 font-semibold text-white/70">Role</th>
                            <th class="text-left py-1.5 pr-4 font-semibold text-white/70">Email</th>
                            <th class="text-left py-1.5 font-semibold text-white/70">Password</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @foreach([
                            ['Dosen',     'dosen@pnj.ac.id',      'password123'],
                            ['Mahasiswa', 'mahasiswa@pnj.ac.id',  'password123'],
                            ['P3M',       'p3m@pnj.ac.id',        'password123'],
                            ['RTPU',      'rtpu@pnj.ac.id',       'password123'],
                            ['Pimpinan',  'pimpinan@pnj.ac.id',   'password123'],
                            ['Eksternal', 'eksternal@gmail.com',   'password123'],
                        ] as [$role, $email, $pass])
                        <tr class="hover:bg-white/5 cursor-pointer" onclick="fillLogin('{{ $email }}')">
                            <td class="py-1.5 pr-4 font-medium">{{ $role }}</td>
                            <td class="py-1.5 pr-4 font-mono text-blue-200">{{ $email }}</td>
                            <td class="py-1.5 font-mono text-blue-200">{{ $pass }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-white/50 text-xs mt-2">Klik baris untuk mengisi email otomatis.</p>
        </div>

    </div>

    <script>
        function fillLogin(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password123';
            document.getElementById('password').focus();
        }
    </script>

</body>
</html>
