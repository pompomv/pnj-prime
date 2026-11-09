<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="page-title">Registrasi Akun Personal – PNJ Prime</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #F5EDE8; }
        .input-field {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 0.8125rem;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            background-color: #FAFAFA;
            color: #374151;
        }
        .input-field:focus {
            border-color: #0D6B5E;
            box-shadow: 0 0 0 3px rgba(13, 107, 94, 0.15);
        }
        .input-field::placeholder { color: #9CA3AF; font-size: 0.775rem; }
        .input-with-icon { padding-left: 36px; }
        .card-shadow { box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .btn-teal { background-color: #0D6B5E; }
        .btn-teal:hover { background-color: #0A5549; }
        .tab-active { background-color: #E6F4F1; border: 2px solid #0D6B5E; color: #0D6B5E; }
        .tab-inactive { background-color: #FAFAFA; border: 2px solid #E5E7EB; color: #6B7280; }
        .section-label { color: #0D6B5E; }
        .upload-box {
            border: 2px dashed #D1D5DB;
            border-radius: 10px;
            padding: 20px 12px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.15s, background-color 0.15s;
            background-color: #FAFAFA;
        }
        .upload-box:hover { border-color: #0D6B5E; background-color: #F0FAF8; }
        .upload-box input[type="file"] { display: none; }
        /* Tab indicator underline */
        .tab-btn { position: relative; transition: all 0.2s; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center py-8 px-4">

    {{-- Card --}}
    <div class="w-full bg-white card-shadow rounded-2xl overflow-hidden" style="max-width: 460px;">

        {{-- Header Bar --}}
        <div class="px-7 pt-7 pb-5">
            {{-- Logo row --}}
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: #E6F4F1;">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                            <path d="M12 3L1 9L12 15L23 9L12 3Z" fill="#0D6B5E"/>
                            <path d="M5 11.09V17C8.09 20.42 15.91 20.42 19 17V11.09L12 15L5 11.09Z" fill="#0D6B5E" opacity="0.7"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-extrabold tracking-widest" style="color: #0D6B5E;">PNJ PRIME</div>
                        <div class="text-xs text-gray-400 leading-tight">DIREKTORAT KERJASAMA & PENDIDIKAN VOKASI</div>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full"
                    style="background-color: #E6F4F1; color: #0D6B5E;">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                    Portal Registrasi Akun
                </div>
            </div>

            <h1 class="text-2xl font-bold text-gray-900">Daftar Akun PNJ Prime</h1>
            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                Lengkapi data identitas dan verifikasi dokumen (KYC) untuk mulai mengakses
                pelatihan, sertifikasi vokasi, dan layanan riset Politeknik Negeri Jakarta.
            </p>
        </div>

        {{-- Tab Switcher --}}
        <div class="px-7 mb-5">
            <div class="text-xs text-gray-400 text-center mb-3 font-medium">Pilih Jenis Akun Pendaftaran</div>
            <div class="grid grid-cols-2 gap-3">
                <button type="button" id="tab-personal" onclick="switchTab('personal')"
                    class="tab-btn tab-active rounded-xl p-3 text-left">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-xs font-bold">Personal / Individu</span>
                    </div>
                    <p class="text-xs text-gray-500 leading-tight">Untuk mahasiswa, alumni, profesional, & publik umum</p>
                </button>
                <button type="button" id="tab-perusahaan" onclick="switchTab('perusahaan')"
                    class="tab-btn tab-inactive rounded-xl p-3 text-left">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="text-xs font-bold">Perusahaan / Mitra Industri</span>
                    </div>
                    <p class="text-xs text-gray-500 leading-tight">Untuk badan usaha, lembaga riset, vendor & mitra industri</p>
                </button>
            </div>
        </div>

        {{-- ═══════════ FORM PERSONAL ═══════════ --}}
        <form id="form-personal" action="{{ route('register.personal.post') }}" method="POST" enctype="multipart/form-data"
            class="px-7 pb-7">
            @csrf
            <input type="hidden" name="jenis_akun" value="personal">

            {{-- Section 1 --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="w-6 h-6 rounded-full text-white text-xs font-bold flex items-center justify-center" style="background-color: #0D6B5E;">1</span>
                <span class="text-sm font-bold" style="color: #374151;">Informasi Akun Utama</span>
                <div class="flex-1 h-px bg-gray-100 ml-1"></div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap (Sesuai KTP) <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" placeholder="misal: Budi Santoso, S.T."
                        value="{{ old('nama_lengkap') }}" required class="input-field">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Alamat Email Aktif <span class="text-red-500">*</span></label>
                    <input type="email" name="email" placeholder="nama@domain.com atau corporate"
                        value="{{ old('email') }}" required class="input-field">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kata Sandi <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="p-pwd" placeholder="Minimal 8 karakter"
                            required class="input-field pr-9">
                        <button type="button" onclick="togglePassword('p-pwd', this)"
                            class="absolute inset-y-0 right-2.5 flex items-center text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Konfirmasi Kata Sandi <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="p-pwd2" placeholder="Ulangi kata sandi"
                            required class="input-field pr-9">
                        <button type="button" onclick="togglePassword('p-pwd2', this)"
                            class="absolute inset-y-0 right-2.5 flex items-center text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Section 2 --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="w-6 h-6 rounded-full text-white text-xs font-bold flex items-center justify-center" style="background-color: #0D6B5E;">2</span>
                <span class="text-sm font-bold text-gray-700">Identitas Personal & Verifikasi</span>
                <div class="flex-1 h-px bg-gray-100"></div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background-color: #E6F4F1; color: #0D6B5E;">Personal / Peneliti Mandiri</span>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Nomor Induk Kependudukan (NIK / KTP) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nik" maxlength="16" placeholder="16 Digit NIK KTP Resmi"
                        value="{{ old('nik') }}" required class="input-field">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        NPWP Pribadi (Format 16 Digit) <span class="text-xs text-gray-400 font-normal">(Opsional / Wajib Pajak)</span>
                    </label>
                    <input type="text" name="npwp" maxlength="16" placeholder="16 Digit NPWP Pribadi (Opsional)"
                        value="{{ old('npwp') }}" class="input-field">
                    <p class="text-xs text-gray-400 mt-1 leading-tight">Digunakan untuk bukti potong pajak / administrasi pelatihan bersertifikat & honorarium riset.</p>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold text-gray-700 mb-2">Unggah Dokumen Identitas & Pajak Pribadi</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="upload-box">
                        <input type="file" name="foto_ktp" accept=".jpg,.jpeg,.png,.pdf">
                        <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div class="text-xs font-semibold text-gray-600">Unggah Foto KTP / Paspor <span class="text-red-500">*</span></div>
                        <div class="text-xs text-gray-400 mt-0.5">Format JPG, PNG, PDF (Maks. 5MB)</div>
                    </label>
                    <label class="upload-box">
                        <input type="file" name="foto_npwp" accept=".jpg,.jpeg,.png,.pdf">
                        <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="text-xs font-semibold text-gray-600">Unggah Foto / Salinan NPWP Pribadi <span class="text-xs text-gray-400">(Opsional)</span></div>
                        <div class="text-xs text-gray-400 mt-0.5">Format JPG, PNG, PDF (Maks. 5MB)</div>
                    </label>
                </div>
            </div>

            {{-- Error messages --}}
            @if (isset($errors) && $errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-600 text-xs">• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Terms --}}
            <label class="flex items-start gap-2.5 cursor-pointer mb-4">
                <input type="checkbox" name="agree_terms" required class="mt-0.5 w-3.5 h-3.5 rounded accent-teal-600 flex-shrink-0">
                <span class="text-xs text-gray-600">
                    Saya menyetujui <a href="#" class="font-semibold underline" style="color: #0D6B5E;">Syarat & Ketentuan</a>
                    serta <a href="#" class="font-semibold underline" style="color: #0D6B5E;">Kebijakan Privasi</a> PNJ Prime.
                </span>
            </label>

            {{-- Submit --}}
            <button type="submit"
                class="flex items-center justify-center gap-2 w-full text-white text-sm font-semibold py-3 rounded-xl transition btn-teal mb-4">
                Daftar Sekarang
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </button>

            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-500">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-semibold" style="color: #0D6B5E;">Masuk di sini</a>
                </p>
                <div class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-xs text-gray-400">SSL • Keamanan Data PNJ Terjamin</span>
                </div>
            </div>
        </form>

        {{-- ═══════════ FORM PERUSAHAAN ═══════════ --}}
        <form id="form-perusahaan" action="{{ route('register.perusahaan.post') }}" method="POST" enctype="multipart/form-data"
            class="px-7 pb-7 hidden">
            @csrf
            <input type="hidden" name="jenis_akun" value="perusahaan">

            {{-- Section 1 --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="w-6 h-6 rounded-full text-white text-xs font-bold flex items-center justify-center" style="background-color: #0D6B5E;">1</span>
                <span class="text-sm font-bold text-gray-700">Informasi Penanggung Jawab (PIC)</span>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap PIC (Sesuai KTP) <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pic" placeholder="misal: Ir. Bambang Wicaksono, M.M."
                        class="input-field">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Alamat Email Resmi Perusahaan <span class="text-red-500">*</span></label>
                    <input type="email" name="email" placeholder="nama@perusahaan.co.id"
                        class="input-field">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kata Sandi <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="c-pwd" name="password" placeholder="Minimal 8 karakter"
                            class="input-field pr-9">
                        <button type="button" onclick="togglePassword('c-pwd', this)"
                            class="absolute inset-y-0 right-2.5 flex items-center text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Konfirmasi Kata Sandi <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="c-pwd2" name="password_confirmation" placeholder="Ulangi kata sandi"
                            class="input-field pr-9">
                        <button type="button" onclick="togglePassword('c-pwd2', this)"
                            class="absolute inset-y-0 right-2.5 flex items-center text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Section 2 --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="w-6 h-6 rounded-full text-white text-xs font-bold flex items-center justify-center" style="background-color: #0D6B5E;">2</span>
                <span class="text-sm font-bold text-gray-700">Legalitas & Profil Perusahaan</span>
                <div class="flex-1 h-px bg-gray-100"></div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background-color: #E6F4F1; color: #0D6B5E;">Badan Usaha / Institusi</span>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Resmi Perusahaan <span class="text-red-500">*</span></label>
                <input type="text" name="nama_perusahaan" placeholder="PT Rekayasa Teknologi Nusantara"
                    class="input-field">
            </div>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Induk Berusaha (NIB 13 Digit) <span class="text-red-500">*</span></label>
                    <input type="text" name="nib" maxlength="13" placeholder="13 Digit Angka Resmi OSS"
                        class="input-field">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">NPWP Perusahaan (Format 16 Digit) <span class="text-red-500">*</span></label>
                    <input type="text" name="npwp_perusahaan" maxlength="16" placeholder="16 Digit NPWP Resmi Ditjen Pajak"
                        class="input-field">
                </div>
            </div>
            <div class="mb-5">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Alamat Domisili Kantor / Institusi <span class="text-red-500">*</span></label>
                <textarea name="alamat" rows="2" placeholder="Alamat lengkap gedung, jalan, kota, dan kode pos"
                    class="input-field resize-none"></textarea>
            </div>

            {{-- Section 3 --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="w-6 h-6 rounded-full text-white text-xs font-bold flex items-center justify-center" style="background-color: #0D6B5E;">3</span>
                <span class="text-sm font-bold text-gray-700">Unggah Dokumen Legalitas Perusahaan</span>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-5">
                <label class="upload-box">
                    <input type="file" name="dok_npwp" accept=".jpg,.jpeg,.png,.pdf">
                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <div class="text-xs font-semibold text-gray-600">Unggah NPWP Perusahaan <span class="text-red-500">*</span></div>
                    <div class="text-xs text-gray-400 mt-0.5">Format JPG, PNG, PDF (Maks. 5MB)</div>
                </label>
                <label class="upload-box">
                    <input type="file" name="dok_nib_akta" accept=".jpg,.jpeg,.png,.pdf">
                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <div class="text-xs font-semibold text-gray-600">Unggah NIB / Akta Perusahaan <span class="text-red-500">*</span></div>
                    <div class="text-xs text-gray-400 mt-0.5">Format PDF resmi OSS/Kemenkumham (Maks. 10MB)</div>
                </label>
            </div>

            {{-- Error messages --}}
            @if (isset($errors) && $errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-600 text-xs">• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Disclaimer --}}
            <label class="flex items-start gap-2.5 cursor-pointer mb-4">
                <input type="checkbox" name="agree_disclaimer" required class="mt-0.5 w-3.5 h-3.5 rounded accent-teal-600 flex-shrink-0">
                <span class="text-xs text-gray-600">
                    Saya menyatakan dengan sesungguhnya bahwa data perusahaan dan dokumen legalitas yang dilampirkan adalah benar dan sah sesuai ketentuan PNJ Prime.
                </span>
            </label>

            {{-- Submit --}}
            <button type="submit"
                class="flex items-center justify-center gap-2 w-full text-white text-sm font-semibold py-3 rounded-xl transition btn-teal mb-4">
                Daftar Akun Perusahaan
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </button>

            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-500">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-semibold" style="color: #0D6B5E;">Masuk di sini</a>
                </p>
                <div class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-xs text-gray-400">SSL • Keamanan Data PNJ Terjamin</span>
                </div>
            </div>
        </form>

    </div>

    {{-- Footer --}}
    <p class="mt-6 text-xs text-gray-400 text-center">
        Politeknik Negeri Jakarta &bull; Platform Layanan Resmi
    </p>

    <script>
        function switchTab(type) {
            const tabPersonal   = document.getElementById('tab-personal');
            const tabPerusahaan = document.getElementById('tab-perusahaan');
            const formPersonal  = document.getElementById('form-personal');
            const formPerusahaan= document.getElementById('form-perusahaan');
            const title         = document.getElementById('page-title');

            if (type === 'personal') {
                tabPersonal.className   = 'tab-btn tab-active rounded-xl p-3 text-left';
                tabPerusahaan.className = 'tab-btn tab-inactive rounded-xl p-3 text-left';
                formPersonal.classList.remove('hidden');
                formPerusahaan.classList.add('hidden');
                title.textContent = 'Registrasi Akun Personal – PNJ Prime';
            } else {
                tabPersonal.className   = 'tab-btn tab-inactive rounded-xl p-3 text-left';
                tabPerusahaan.className = 'tab-btn tab-active rounded-xl p-3 text-left';
                formPersonal.classList.add('hidden');
                formPerusahaan.classList.remove('hidden');
                title.textContent = 'Registrasi Akun Perusahaan – PNJ Prime';
            }
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

        // Handle file upload preview
        document.querySelectorAll('.upload-box input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const label = this.closest('.upload-box');
                const fileName = this.files[0]?.name;
                if (fileName) {
                    const textEl = label.querySelector('.text-xs.font-semibold');
                    if (textEl) textEl.textContent = '✓ ' + fileName.substring(0, 20) + (fileName.length > 20 ? '…' : '');
                    label.style.borderColor = '#0D6B5E';
                    label.style.backgroundColor = '#F0FAF8';
                }
            });
        });

        // Pre-select tab from URL hash if available
        if (window.location.hash === '#perusahaan') switchTab('perusahaan');
    </script>
</body>
</html>

