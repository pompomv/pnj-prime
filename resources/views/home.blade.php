<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PNJ PRIME – Hilirisasi Inovasi & Layanan Riset Terapan Politeknik Negeri Jakarta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        teal: {
                            50: '#F0FAF8',
                            100: '#E6F4F1',
                            200: '#C2E5DE',
                            600: '#0E7D6F',
                            700: '#0D6B5E',
                            800: '#0A5549',
                            900: '#063A32',
                        },
                        cream: {
                            50: '#FDFBF9',
                            100: '#FBF7F4',
                            200: '#F5EDE8',
                            300: '#EFE3DC',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #FBF7F4;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1F2937;
        }
        .bg-teal-primary { background-color: #0D6B5E; }
        .bg-teal-primary:hover { background-color: #0A5549; }
        .text-teal-primary { color: #0D6B5E; }
        .border-teal-primary { border-color: #0D6B5E; }
        .card-shadow {
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.03);
        }
        .card-shadow-hover {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card-shadow-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px -4px rgba(13, 107, 94, 0.12), 0 6px 12px -2px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-teal-100 selection:text-teal-900">

    {{-- ═══════════════════════════════════════════════════════════════════════
         1. NAVBAR / HEADER
    ══════════════════════════════════════════════════════════════════════════ --}}
    <header class="bg-white/95 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">

                {{-- Left: Logo --}}
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-transform group-hover:scale-105" style="background-color: #E6F4F1;">
                            {{-- Cap Icon --}}
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 3L1 9L12 15L23 9L12 3Z" fill="#0D6B5E"/>
                                <path d="M5 11.09V17C8.09 20.42 15.91 20.42 19 17V11.09L12 15L5 11.09Z" fill="#0D6B5E" opacity="0.75"/>
                                <path d="M22 9V15" stroke="#0D6B5E" stroke-width="1.75" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold tracking-tight text-gray-900 block leading-tight group-hover:text-teal-700 transition">
                                PNJ PRIME
                            </span>
                            <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider block">
                                Hilirisasi Riset Terapan
                            </span>
                        </div>
                    </a>
                </div>

                {{-- Center-Right: Navigation Links --}}
                <nav class="hidden md:flex items-center gap-7 text-xs font-semibold text-gray-600">
                    <a href="{{ route('home') }}" class="text-teal-primary font-bold hover:text-teal-800 transition">
                        Beranda
                    </a>
                    <a href="{{ route('katalog.produk') }}" class="hover:text-teal-primary transition">
                        Katalog Inovasi
                    </a>
                    <a href="{{ route('katalog.lab') }}" class="hover:text-teal-primary transition">
                        Layanan Lab
                    </a>
                    <a href="#alur-kemitraan" class="hover:text-teal-primary transition">
                        Alur Kemitraan
                    </a>
                    <a href="#tentang-kami" class="hover:text-teal-primary transition">
                        Tentang Kami
                    </a>
                </nav>

                {{-- Right: User / Auth Actions --}}
                <div class="flex items-center gap-3 shrink-0">
                    @auth
                        {{-- Logged In User --}}
                        <div class="flex items-center gap-2.5">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-teal-800 bg-teal-100 hover:bg-teal-200 transition">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Dashboard ({{ ucfirst(auth()->user()->role) }})
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-2 text-xs font-semibold text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-xl transition" title="Keluar">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- Guest Links --}}
                        <a href="{{ route('sso.login') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-gray-700 hover:text-teal-primary hover:bg-gray-100 transition">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            SSO PNJ
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-teal-primary hover:bg-teal-800 transition shadow-sm hover:shadow">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Masuk Akun
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </header>


    {{-- ═══════════════════════════════════════════════════════════════════════
         2. HERO SECTION
    ══════════════════════════════════════════════════════════════════════════ --}}
    <section class="py-12 lg:py-16 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                {{-- Left Column: Text & CTAs --}}
                <div class="lg:col-span-7 space-y-6">

                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold tracking-wide" style="background-color: #E6F4F1; color: #0D6B5E;">
                        <span class="w-2 h-2 rounded-full bg-teal-primary"></span>
                        HILIRISASI RISET & PRODUK TERAPAN VOKASI
                    </div>

                    {{-- Main Headline --}}
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 tracking-tight leading-[1.15]">
                        Hilirisasi Inovasi & Layanan Riset Terapan Politeknik Negeri Jakarta
                    </h1>

                    {{-- Subheadline --}}
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed max-w-2xl font-normal">
                        Katalog produk riset terapan, lisensi komersial/paten, pengujian laboratorium terakreditasi, dan jasa konsultasi kepakaran vokasi langsung dari kampus ke dunia industri.
                    </p>

                    {{-- Action Buttons --}}
                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ route('katalog.produk') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-xs sm:text-sm font-bold text-white bg-teal-primary hover:bg-teal-800 transition shadow-sm hover:shadow">
                            Jelajahi Katalog Produk
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="{{ route('katalog.lab') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-xs sm:text-sm font-bold text-gray-800 bg-white border border-gray-300 hover:border-gray-400 hover:bg-gray-50 transition shadow-sm">
                            Konsultasi Layanan Jasa
                        </a>
                    </div>

                    {{-- Stats Counter Row --}}
                    <div class="grid grid-cols-3 gap-4 pt-6 border-t border-gray-200/80 max-w-lg">
                        <div>
                            <div class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">45+</div>
                            <div class="text-[11px] sm:text-xs text-gray-500 font-medium mt-0.5 leading-tight">Produk Inovasi Siap Pakai</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">12</div>
                            <div class="text-[11px] sm:text-xs text-gray-500 font-medium mt-0.5 leading-tight">Paten Terlisensi</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">200+</div>
                            <div class="text-[11px] sm:text-xs text-gray-500 font-medium mt-0.5 leading-tight">Kemitraan Industri</div>
                        </div>
                    </div>

                </div>

                {{-- Right Column: Featured Visual Card --}}
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        {{-- Background decorative blur --}}
                        <div class="absolute -inset-2 bg-gradient-to-r from-teal-200/50 to-emerald-200/30 rounded-3xl blur-2xl -z-10"></div>

                        {{-- Card container --}}
                        <div class="bg-white rounded-2xl overflow-hidden card-shadow border border-gray-200/80 group">
                            {{-- Image Container --}}
                            <div class="relative h-64 sm:h-72 w-full overflow-hidden bg-gray-100">
                                <img
                                    src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80"
                                    alt="Pusat Riset Mesin Presisi PNJ"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-extrabold text-teal-800 tracking-wide uppercase">
                                    Fasilitas Lab Terakreditasi
                                </div>
                            </div>

                            {{-- Pinned Info Card at Bottom --}}
                            <div class="p-5 bg-white">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="text-[11px] font-bold text-teal-primary tracking-wide uppercase">Inovasi Unggulan BLU PNJ</div>
                                        <h3 class="text-base font-bold text-gray-900 mt-0.5">Trainer Robo-Welder Otomasi Vokasi</h3>
                                        <p class="text-xs text-gray-500 mt-1">Pusat Unggulan Teknologi Mesin Presisi &amp; Robotika Industri PNJ</p>
                                    </div>
                                    <a href="{{ route('marketplace.index') }}" class="shrink-0 px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-teal-primary hover:bg-teal-800 transition">
                                        Detail &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════════════════
         3. KATEGORI LAYANAN UNGGULAN
    ══════════════════════════════════════════════════════════════════════════ --}}
    <section class="py-12 border-t border-gray-200/60 bg-white/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header Row --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                        Kategori Layanan Unggulan
                    </h2>
                </div>
                <div class="max-w-md">
                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                        Temukan inovasi dan keahlian vokasi yang siap menjawab kebutuhan industri dengan standar mutu pendidikan tinggi terapan.
                    </p>
                </div>
            </div>

            {{-- Two Category Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Card 1: Produk Riset & Inovasi Terapan --}}
                <div class="bg-white rounded-2xl p-7 card-shadow border border-gray-200/80 card-shadow-hover flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5" style="background-color: #E6F4F1;">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="#0D6B5E" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            Produk Riset &amp; Inovasi Terapan
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed mb-6">
                            Perangkat keras manufaktur, modul kontrol otomatisasi industri, sistem perangkat lunak terintegrasi, dan produk hayati bernilai komersial hasil riset dosen dan mahasiswa.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('katalog.produk') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-primary hover:text-teal-900 group">
                            Lihat Katalog Produk
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Card 2: Jasa Pengetesan / Pengujian Lab & Kalibrasi --}}
                <div class="bg-white rounded-2xl p-7 card-shadow border border-gray-200/80 card-shadow-hover flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5" style="background-color: #E6F4F1;">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="#0D6B5E" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            Jasa Pengetesan / Pengujian Lab &amp; Kalibrasi
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed mb-6">
                            Pengujian mekanik, kimia analitis, kalibrasi instrumen, serta sertifikasi kelaikan teknis laboratorium bersertifikat standar industri oleh tim ahli bersertifikat BNSP.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('katalog.lab') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-primary hover:text-teal-900 group">
                            Cari Layanan Jasa
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════════════════
         4. SOROTAN INOVASI TERKINI
    ══════════════════════════════════════════════════════════════════════════ --}}
    <section class="py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header Row --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                        Sorotan Inovasi Terkini
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1 max-w-xl">
                        Produk teknologi dan karya inovasi terbaru dari sivitas akademika yang siap diproduksi massal atau diterapkan langsung di industri mitra.
                    </p>
                </div>
                <div>
                    <a href="{{ route('marketplace.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-primary hover:text-teal-900 transition">
                        Lihat Semua Inovasi ({{ max($totalInovasi, 3) }})
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- 3 Product / Service Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- CARD 1: Smart IoT Monitoring Panel --}}
                <div class="bg-white rounded-2xl overflow-hidden card-shadow border border-gray-200/80 card-shadow-hover flex flex-col justify-between">
                    <div>
                        {{-- Image with type badge --}}
                        <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                            <img
                                src="https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80"
                                alt="Smart IoT Monitoring Panel"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                            >
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider bg-white/90 backdrop-blur-md text-teal-800 uppercase shadow-sm">
                                PRODUK FISIK
                            </span>
                        </div>
                        <div class="p-5">
                            <div class="text-[11px] font-semibold text-gray-400 mb-1">Teknik Elektro &amp; Mekatronika</div>
                            <h3 class="text-base font-bold text-gray-900 mb-1.5 line-clamp-1">
                                Smart IoT Monitoring Panel 3-Phase
                            </h3>
                            <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                                Sistem monitoring kestabilan kelistrikan cerdas berbasis IoT dengan alert real-time suhu dan daya transformator pabrik.
                            </p>
                        </div>
                    </div>
                    <div class="px-5 pb-5 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-gray-400 font-semibold uppercase">Harga Patokan BLU</div>
                            <div class="text-sm font-extrabold text-teal-primary">Rp 4.500.000</div>
                        </div>
                        <a href="{{ route('marketplace.index') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-gray-700 bg-gray-100 hover:bg-teal-primary hover:text-white transition">
                            Detail &rarr;
                        </a>
                    </div>
                </div>

                {{-- CARD 2: Solar Turbin Mini Water --}}
                <div class="bg-white rounded-2xl overflow-hidden card-shadow border border-gray-200/80 card-shadow-hover flex flex-col justify-between">
                    <div>
                        {{-- Image with type badge --}}
                        <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                            <img
                                src="https://images.unsplash.com/photo-1509391365360-2e959784a276?auto=format&fit=crop&w=600&q=80"
                                alt="Solar Turbin Mini Water"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                            >
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider bg-white/90 backdrop-blur-md text-teal-800 uppercase shadow-sm">
                                PRODUK FISIK
                            </span>
                        </div>
                        <div class="p-5">
                            <div class="text-[11px] font-semibold text-gray-400 mb-1">Teknik Mesin &amp; Konversi Energi</div>
                            <h3 class="text-base font-bold text-gray-900 mb-1.5 line-clamp-1">
                                Solar Turbin Mini Water &amp; Irigasi
                            </h3>
                            <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                                Pembangkit listrik mikro hidro-solar ramah lingkungan untuk pengairan pertanian lahan kering dan tambak mandiri energi.
                            </p>
                        </div>
                    </div>
                    <div class="px-5 pb-5 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-gray-400 font-semibold uppercase">Harga Patokan BLU</div>
                            <div class="text-sm font-extrabold text-teal-primary">Rp 12.000.000</div>
                        </div>
                        <a href="{{ route('marketplace.index') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-gray-700 bg-gray-100 hover:bg-teal-primary hover:text-white transition">
                            Detail &rarr;
                        </a>
                    </div>
                </div>

                {{-- CARD 3: Uji Kalibrasi Sensor & Material --}}
                <div class="bg-white rounded-2xl overflow-hidden card-shadow border border-gray-200/80 card-shadow-hover flex flex-col justify-between">
                    <div>
                        {{-- Image with type badge --}}
                        <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                            <img
                                src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80"
                                alt="Uji Kalibrasi Sensor"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                            >
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider bg-white/90 backdrop-blur-md text-amber-800 uppercase shadow-sm">
                                LAYANAN JASA
                            </span>
                        </div>
                        <div class="p-5">
                            <div class="text-[11px] font-semibold text-gray-400 mb-1">Laboratorium Instrumentasi Vokasi</div>
                            <h3 class="text-base font-bold text-gray-900 mb-1.5 line-clamp-1">
                                Uji Kalibrasi Sensor &amp; Material Industri
                            </h3>
                            <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                                Layanan pengujian presisi akurasi sensor suhu, tekanan, dan kekerasan material dengan sertifikat resmi P3M PNJ.
                            </p>
                        </div>
                    </div>
                    <div class="px-5 pb-5 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-gray-400 font-semibold uppercase">Tarif Layanan BLU</div>
                            <div class="text-sm font-extrabold text-teal-primary">Rp 750.000 <span class="text-[10px] font-normal text-gray-400">/ uji</span></div>
                        </div>
                        <a href="{{ route('marketplace.index', ['tipe' => 'jasa']) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-gray-700 bg-gray-100 hover:bg-teal-primary hover:text-white transition">
                            Detail &rarr;
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════════════════
         5. ALUR TRANSAKSI & KEMITRAAN RESMI (4 STEPS)
    ══════════════════════════════════════════════════════════════════════════ --}}
    <section id="alur-kemitraan" class="py-16 border-t border-gray-200/70 bg-white/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            {{-- Top Badge --}}
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide mb-3" style="background-color: #E6F4F1; color: #0D6B5E;">
                <span>✦</span>
                ALUR RESMI &amp; TRANSPARAN
            </div>

            {{-- Title & Subtitle --}}
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                Alur Transaksi &amp; Kemitraan Resmi
            </h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-2 max-w-2xl mx-auto leading-relaxed">
                Sistem transaksi komersialisasi resmi yang terhubung langsung dengan Rekening BLU Politeknik Negeri Jakarta dengan kepatuhan tata kelola keuangan negara.
            </p>

            {{-- 4 Steps Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-12 text-left">

                {{-- Step 1 --}}
                <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-200/80 relative flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background-color: #E6F4F1;">
                                <svg class="w-5 h-5 text-teal-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-extrabold text-gray-300">01</span>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Pilih Inovasi / Layanan</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Eksplorasi spesifikasi teknis produk terapan atau tentukan kebutuhan pengujian laboratorium terstandar.
                        </p>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-200/80 relative flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background-color: #E6F4F1;">
                                <svg class="w-5 h-5 text-teal-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-extrabold text-gray-300">02</span>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Verifikasi Akun &amp; Legalitas</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Registrasi akun mitra industri dengan validasi NIB/NPWP untuk verifikasi kepatuhan transaksi resmi BLU.
                        </p>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-200/80 relative flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background-color: #E6F4F1;">
                                <svg class="w-5 h-5 text-teal-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-extrabold text-gray-300">03</span>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Pembayaran Rekening Resmi</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Pembayaran resmi langsung disetorkan ke Rekening BLU Politeknik Negeri Jakarta (Mandiri / BNI).
                        </p>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-200/80 relative flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background-color: #E6F4F1;">
                                <svg class="w-5 h-5 text-teal-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-extrabold text-gray-300">04</span>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Serah Terima &amp; Sertifikat</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Pengiriman produk inovasi, penjadwalan layanan jasa, serta penerbitan laporan kelaikan &amp; sertifikat resmi.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════════════════
         6. CTA BANNER (SIAP BERKOLABORASI DENGAN KAMPUS VOKASI PNJ?)
    ══════════════════════════════════════════════════════════════════════════ --}}
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-teal-primary rounded-3xl p-8 sm:p-12 text-white relative overflow-hidden shadow-xl">

                {{-- Decorative background shapes --}}
                <div class="absolute -right-12 -top-12 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
                <div class="absolute right-36 -bottom-16 w-80 h-80 rounded-full bg-white/5 pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <div class="max-w-2xl space-y-3">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-teal-100">
                            <span>✦</span>
                            KOLABORASI RISET INDUSTRI
                        </div>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight">
                            Siap Berkolaborasi dengan Kampus Vokasi PNJ?
                        </h2>
                        <p class="text-xs sm:text-sm text-teal-100 leading-relaxed font-normal">
                            Dapatkan solusi teknologi terapan inovatif, pengujian lab terstandar, dan kemitraan riset komersial langsung bersama para ahli dan periset terkemuka Politeknik Negeri Jakarta.
                        </p>
                    </div>

                    <div class="shrink-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl text-xs sm:text-sm font-bold text-teal-900 bg-white hover:bg-teal-50 transition shadow-lg">
                            Daftar Akun Mitra
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl text-xs sm:text-sm font-bold text-white border border-white/30 hover:bg-white/10 transition">
                            Masuk Akun
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════════════════
         7. FOOTER & TENTANG KAMI
    ══════════════════════════════════════════════════════════════════════════ --}}
    <footer id="tentang-kami" class="bg-white border-t border-gray-200 mt-auto pt-14 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">

                {{-- Column 1 & 2: Brand & Address --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: #E6F4F1;">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                                <path d="M12 3L1 9L12 15L23 9L12 3Z" fill="#0D6B5E"/>
                                <path d="M5 11.09V17C8.09 20.42 15.91 20.42 19 17V11.09L12 15L5 11.09Z" fill="#0D6B5E" opacity="0.7"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-lg font-extrabold text-gray-900 tracking-tight block">PNJ PRIME</span>
                            <span class="text-[10px] text-gray-400 font-semibold uppercase">Platform Hilirisasi Riset Terapan</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 leading-relaxed pr-6">
                        Pusat hilirisasi riset, e-commerce teknologi terapan, pengujian laboratorium, dan sertifikasi vokasi Politeknik Negeri Jakarta di bawah kelolaan P3M dan RTPU.
                    </p>
                    <div class="text-xs text-gray-500 space-y-1">
                        <p class="font-medium text-gray-700">Politeknik Negeri Jakarta</p>
                        <p>Jl. Prof. Dr. G.A. Siwabessy, Kampus Universitas Indonesia, Depok 16425</p>
                        <p>Email: <a href="mailto:pnjprime@pnj.ac.id" class="text-teal-primary hover:underline">pnjprime@pnj.ac.id</a> &bull; Telp: (021) 7270036</p>
                    </div>
                </div>

                {{-- Column 3: Layanan & Inovasi --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900">Layanan Inovasi</h4>
                    <ul class="space-y-2 text-xs text-gray-500">
                        <li><a href="{{ route('marketplace.index', ['tipe' => 'produk_fisik']) }}" class="hover:text-teal-primary transition">Produk Hasil Riset</a></li>
                        <li><a href="{{ route('marketplace.index', ['tipe' => 'jasa']) }}" class="hover:text-teal-primary transition">Pengujian Lab &amp; Kalibrasi</a></li>
                        <li><a href="{{ route('marketplace.index') }}" class="hover:text-teal-primary transition">Paten &amp; Hak Cipta</a></li>
                        <li><a href="{{ route('marketplace.index') }}" class="hover:text-teal-primary transition">Konsultasi Kepakaran</a></li>
                    </ul>
                </div>

                {{-- Column 4: Tata Kelola & BLU --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900">Tata Kelola &amp; Akun</h4>
                    <ul class="space-y-2 text-xs text-gray-500">
                        <li><a href="{{ route('sso.login') }}" class="hover:text-teal-primary transition">Portal SSO Civitas Kampus</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-teal-primary transition">Pendaftaran Mitra Industri (KYC)</a></li>
                        <li><a href="{{ route('register') }}#perusahaan" class="hover:text-teal-primary transition">Registrasi Badan Usaha / PT</a></li>
                        <li><a href="#" class="hover:text-teal-primary transition">Kepatuhan Rekening BLU</a></li>
                    </ul>
                </div>

                {{-- Column 5: Verifikasi & Keamanan --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900">Keamanan &amp; Standar</h4>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-200/80 space-y-2">
                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-teal-primary">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Tersertifikasi SSL 256-Bit
                        </div>
                        <p class="text-[10px] text-gray-500 leading-tight">
                            Seluruh data transaksi dan verifikasi dilindungi enkripsi terstandar institusi pemerintah.
                        </p>
                    </div>
                </div>

            </div>

            {{-- Bottom Bar --}}
            <div class="pt-6 border-t border-gray-200/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-400">
                <p>&copy; 2025 Politeknik Negeri Jakarta. Seluruh hak cipta dilindungi undang-undang.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-gray-600 transition">Kebijakan Privasi</a>
                    <span>&bull;</span>
                    <a href="#" class="hover:text-gray-600 transition">Syarat &amp; Ketentuan</a>
                    <span>&bull;</span>
                    <a href="#" class="hover:text-gray-600 transition">Pusat Bantuan UPT TIK</a>
                </div>
            </div>

        </div>
    </footer>

</body>
</html>
