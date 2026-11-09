<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SSO PNJ – PNJ Prime</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: linear-gradient(135deg, #E8F4F2 0%, #F0F9F7 50%, #E6F4F1 100%); }
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
        .card-shadow { box-shadow: 0 4px 32px rgba(0,0,0,0.1); }
        .btn-teal { background-color: #0D6B5E; }
        .btn-teal:hover { background-color: #0A5549; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">

    {{-- Header strip --}}
    <div class="w-full max-w-md bg-gray-900 text-white text-xs py-2 px-4 flex items-center justify-between rounded-t-xl -mb-0 z-10" style="max-width: 440px;">
        <span class="font-medium">PNJ Prime SSO</span>
        <span class="text-gray-400">portal.pnj.ac.id</span>
    </div>

    {{-- Card --}}
    <div class="w-full bg-white card-shadow p-8" style="max-width: 440px;">

        {{-- Logo & Badge --}}
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl mb-3" style="background-color: #E6F4F1;">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none">
                    <path d="M12 3L1 9L12 15L23 9L12 3Z" fill="#0D6B5E"/>
                    <path d="M5 11.09V17C8.09 20.42 15.91 20.42 19 17V11.09L12 15L5 11.09Z" fill="#0D6B5E" opacity="0.7"/>
                </svg>
            </div>
            <div class="inline-block px-3 py-1 rounded-full text-xs font-bold tracking-widest mb-2"
                style="background-color: #FFF3E0; color: #E65100; border: 1px solid #FFCC80;">
                PORTAL SINGLE SIGN-ON (SSO)
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-wide">PNJ PRIME</h1>
            <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                Gunakan Nomor Induk Mahasiswa (NIM) atau NIP/NIDN<br>
                beserta kata sandi akun SSO kampus Anda
            </p>
        </div>

        {{-- Error --}}
        @if (isset($errors) && $errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                @foreach ($errors->all() as $error)
                    <p class="text-red-600 text-xs">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('sso.post') }}" method="POST" class="space-y-4">
            @csrf

            {{-- NIM / NIP / NIDN --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Nomor Induk Mahasiswa (NIM) / NIP / NIDN
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/>
                        </svg>
                    </span>
                    <input type="text" name="nim_nip" id="nim_nip"
                        value="{{ old('nim_nip') }}"
                        placeholder="Contoh: 2107411001 atau NIP Dosen"
                        required autofocus
                        class="input-field">
                </div>
            </div>

            {{-- Password SSO --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-semibold text-gray-700">Kata Sandi (Password) SSO</label>
                    <a href="#" class="text-xs font-semibold" style="color: #0D6B5E;">Lupa Kata Sandi?</a>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input type="password" name="password_sso" id="password_sso"
                        placeholder="Masukkan kata sandi SSO Anda"
                        required
                        class="input-field pr-10">
                    <button type="button" onclick="togglePassword('password_sso', this)"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Remember Device --}}
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember_device" class="w-3.5 h-3.5 rounded accent-teal-600">
                <span class="text-xs text-gray-600">Ingat perangkat ini selama 30 hari</span>
            </label>

            {{-- Submit --}}
            <button type="submit"
                class="flex items-center justify-center gap-2 w-full text-white text-sm font-semibold py-3 rounded-xl transition btn-teal">
                Masuk SSO Kampus
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </button>
        </form>

        {{-- Divider --}}
        <div class="flex items-center gap-3 my-5">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="text-xs text-gray-400 font-medium">ATAU</span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        {{-- Back to External Login --}}
        <a href="{{ route('login') }}"
           class="flex items-center justify-center gap-2 w-full text-sm font-semibold py-3 rounded-xl border-2 transition text-gray-700 hover:bg-gray-50"
           style="border-color: #E5E7EB;">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Bukan Civitas Akademika? Masuk Akun Tamu / Mitra
        </a>

        {{-- Status Info --}}
        <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 bg-green-500 rounded-full inline-block animate-pulse"></span>
                <span class="text-xs text-gray-500">Server SSO PNJ Aktif &bull; TLS 1.3</span>
            </div>
            <a href="#" class="text-xs flex items-center gap-1" style="color: #0D6B5E;">
                Pusat Bantuan UPT TIK
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </div>

        {{-- Akun Demo SSO (Dev Only) --}}
        @if(config('app.env') !== 'production')
        <details class="mt-4">
            <summary class="text-xs text-gray-400 cursor-pointer hover:text-gray-600 text-center">🔧 Akun Demo SSO (Dev Only)</summary>
            <div class="mt-2 overflow-x-auto">
                <table class="w-full text-xs text-gray-600">
                    <thead><tr class="border-b">
                        <th class="text-left py-1 pr-3 text-gray-500">Role</th>
                        <th class="text-left py-1 pr-3 text-gray-500">NIM / NIP</th>
                        <th class="text-left py-1 text-gray-500">Pass</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach([
                            ['Mahasiswa', '2107411001'],
                            ['Dosen',     '197501012000031001'],
                            ['P3M',       '198002022005011002'],
                            ['RTPU',      '198503032010121003'],
                            ['Pimpinan',  '196804041993031004'],
                        ] as [$role, $nim])
                        <tr class="cursor-pointer hover:bg-gray-50" onclick="fillSso('{{ $nim }}')">
                            <td class="py-1 pr-3 font-medium">{{ $role }}</td>
                            <td class="py-1 pr-3 font-mono text-gray-600 text-xs">{{ $nim }}</td>
                            <td class="py-1 font-mono text-gray-500">password123</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="text-gray-400 text-xs mt-1">Klik baris untuk auto-fill.</p>
            </div>
        </details>
        @endif
    </div>

    {{-- Footer --}}
    <div class="w-full text-center py-3" style="max-width: 440px; background-color: rgba(255,255,255,0.6); border-top: 1px solid #E5E7EB;">
        <div class="flex items-center justify-center gap-4 text-xs text-gray-500">
            <span>&copy; 2025 Politeknik Negeri Jakarta &bull; Single Sign-On Terintegrasi</span>
        </div>
        <div class="flex items-center justify-center gap-4 mt-1 text-xs" style="color: #0D6B5E;">
            <a href="#">Panduan Akun</a>
            <span class="text-gray-300">|</span>
            <a href="#">Kebijakan Privasi</a>
            <span class="text-gray-300">|</span>
            <a href="#">Status Layanan</a>
        </div>
    </div>

    <script>
        function fillSso(nim) {
            document.getElementById('nim_nip').value = nim;
            document.getElementById('password_sso').value = 'password123';
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

