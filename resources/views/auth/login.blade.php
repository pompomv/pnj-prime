<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – PNJ Prime</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        teal: {
                            primary: '#0D6B5E',
                            dark: '#0A5549',
                            light: '#E6F4F1',
                        },
                        cream: '#F5EDE8',
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #F5EDE8; }
        .btn-teal { background-color: #0D6B5E; }
        .btn-teal:hover { background-color: #0A5549; }
        .border-teal { border-color: #0D6B5E; }
        .text-teal { color: #0D6B5E; }
        .ring-teal:focus { --tw-ring-color: #0D6B5E; }
        .input-field {
            width: 100%;
            padding: 10px 12px 10px 36px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            background-color: #FAFAFA;
            color: #374151;
        }
        .input-field:focus {
            border-color: #0D6B5E;
            box-shadow: 0 0 0 3px rgba(13, 107, 94, 0.15);
        }
        .input-field::placeholder { color: #9CA3AF; font-size: 0.8rem; }
        .card-shadow { box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">

    {{-- ─── Card ──────────────────────────────────────────────────────────── --}}
    <div class="w-full max-w-sm bg-white rounded-2xl card-shadow p-8">

        {{-- Logo & Title --}}
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl mb-3" style="background-color: #E6F4F1;">
                {{-- Graduation Cap Icon --}}
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 3L1 9L12 15L23 9L12 3Z" fill="#0D6B5E"/>
                    <path d="M5 11.09V17C8.09 20.42 15.91 20.42 19 17V11.09L12 15L5 11.09Z" fill="#0D6B5E" opacity="0.7"/>
                    <path d="M22 9V15" stroke="#0D6B5E" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="text-xs font-semibold tracking-widest mb-1" style="color: #0D6B5E;">PNJ PRIME</div>
            <h1 class="text-xl font-bold text-gray-800 leading-tight">Selamat Datang di PNJ<br>Prime</h1>
            <p class="text-xs text-gray-500 mt-1.5">Pusat Pelatihan, Sertifikasi, dan E-Commerce Edukasi PNJ</p>
        </div>

        {{-- Error --}}
        @if (isset($errors) && $errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                @foreach ($errors->all() as $error)
                    <p class="text-red-600 text-xs">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-700 text-xs">{{ session('success') }}</p>
            </div>
        @endif

        {{-- SSO Button --}}
        <a href="{{ route('sso.login') }}"
           class="flex items-center justify-center gap-2 w-full text-white text-sm font-semibold py-3 rounded-xl transition btn-teal mb-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Login SSO PNJ
        </a>
        <p class="text-center text-xs text-gray-400 mb-5">Civitas Akademika Internal (Dosen, Mahasiswa & Tendik)</p>

        {{-- Divider --}}
        <div class="flex items-center gap-3 mb-5">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="text-xs text-gray-400 font-medium">ATAU</span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        {{-- External Login Section --}}
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Masuk sebagai Pengguna Eksternal</span>
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full text-white" style="background-color: #0D6B5E;">Eksternal</span>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Email --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </span>
                    <input type="email" name="email" id="email"
                        value="{{ old('email') }}"
                        placeholder="nama@instansi.com atau email@domain.com"
                        required autofocus
                        class="input-field @error('email') border-red-400 @enderror">
                </div>
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input type="password" name="password" id="password"
                        placeholder="••••••••"
                        required
                        class="input-field pr-10">
                    <button type="button" onclick="togglePassword('password', this)"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg id="eye-password" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Remember & Forgot --}}
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded accent-teal-600">
                    <span class="text-xs text-gray-600">Ingat Saya</span>
                </label>
                <a href="#" class="text-xs font-semibold" style="color: #0D6B5E;">Lupa Kata Sandi?</a>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="flex items-center justify-center gap-2 w-full text-white text-sm font-semibold py-3 rounded-xl transition btn-teal">
                Masuk ke Akun
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </button>
        </form>

        {{-- Register Link --}}
        <p class="text-center text-xs text-gray-500 mt-5">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold" style="color: #0D6B5E;">Daftar sebagai Pengguna Eksternal</a>
        </p>

        {{-- SSL Badge --}}
        <div class="flex items-center justify-center gap-1.5 mt-5 pt-4 border-t border-gray-100">
            <svg class="w-3.5 h-3.5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
            </svg>
            <span class="text-xs text-gray-400">Terserifikasi SSL • Keamanan Data PNJ</span>
        </div>

        {{-- Demo Akun (untuk dev) --}}
        @if(config('app.env') !== 'production')
        <details class="mt-4">
            <summary class="text-xs text-gray-400 cursor-pointer hover:text-gray-600 text-center">🔧 Akun Demo (Dev Only)</summary>
            <div class="mt-2 overflow-x-auto">
                <table class="w-full text-xs text-gray-600">
                    <thead><tr class="border-b">
                        <th class="text-left py-1 pr-3 text-gray-500">Role</th>
                        <th class="text-left py-1 pr-3 text-gray-500">Email</th>
                        <th class="text-left py-1 text-gray-500">Pass</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach([
                            ['Dosen','dosen@pnj.ac.id'],
                            ['Mahasiswa','mahasiswa@pnj.ac.id'],
                            ['P3M','p3m@pnj.ac.id'],
                            ['RTPU','rtpu@pnj.ac.id'],
                            ['Pimpinan','pimpinan@pnj.ac.id'],
                            ['Eksternal','eksternal@gmail.com'],
                        ] as [$role,$em])
                        <tr class="cursor-pointer hover:bg-gray-50" onclick="fillLogin('{{ $em }}')">
                            <td class="py-1 pr-3 font-medium">{{ $role }}</td>
                            <td class="py-1 pr-3 font-mono text-gray-500 text-xs">{{ $em }}</td>
                            <td class="py-1 font-mono text-gray-500">password123</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="text-gray-400 text-xs mt-1">Klik baris untuk isi otomatis.</p>
            </div>
        </details>
        @endif

    </div>

    {{-- Footer --}}
    <p class="mt-6 text-xs text-gray-400 text-center">
        Politeknik Negeri Jakarta &bull; Platform Pembelajaran Resmi
    </p>

    <script>
        function fillLogin(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password123';
        }
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                btn.querySelector('svg').innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
            } else {
                input.type = 'password';
                btn.querySelector('svg').innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        }
    </script>
</body>
</html>
