<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Inovasi & Layanan Pengujian – PNJ PRIME</title>
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

                {{-- Center-Left: Search Bar with Category Dropdown --}}
                <div class="hidden lg:flex items-center flex-1 max-w-md mx-4">
                    <form action="{{ route('marketplace.index') }}" method="GET" class="w-full flex items-center bg-[#F9FAFB] border border-gray-300 rounded-full py-1.5 pl-4 pr-1.5 text-xs focus-within:border-teal-primary focus-within:ring-2 focus-within:ring-teal-100 transition">
                        <select name="kategori" class="bg-transparent text-gray-700 font-medium pr-2 focus:outline-none cursor-pointer border-r border-gray-200">
                            <option value="semua" {{ ($kategori ?? '') === 'semua' ? 'selected' : '' }}>Semua Kategori</option>
                            <option value="rekayasa" {{ ($kategori ?? '') === 'rekayasa' ? 'selected' : '' }}>Produk Rekayasa</option>
                            <option value="pengujian" {{ ($kategori ?? '') === 'pengujian' ? 'selected' : '' }}>Jasa Pengujian</option>
                            <option value="kalibrasi" {{ ($kategori ?? '') === 'kalibrasi' ? 'selected' : '' }}>Kalibrasi Presisi</option>
                            <option value="pelatihan" {{ ($kategori ?? '') === 'pelatihan' ? 'selected' : '' }}>Pelatihan Vokasi</option>
                        </select>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari inovasi, jasa lab, paten..."
                            class="w-full px-3 py-1 bg-transparent text-gray-800 placeholder-gray-400 focus:outline-none text-xs"
                        >
                        <button type="submit" class="w-8 h-8 rounded-full bg-teal-primary hover:bg-teal-800 text-white flex items-center justify-center shrink-0 transition" title="Cari">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- Center-Right: Navigation Links --}}
                <nav class="hidden md:flex items-center gap-6 text-xs font-semibold text-gray-600">
                    <a href="{{ route('home') }}" class="hover:text-teal-primary transition">
                        Beranda
                    </a>
                    <a href="{{ route('marketplace.index') }}" class="text-teal-primary font-bold transition">
                        Katalog Inovasi
                    </a>
                    <a href="{{ route('marketplace.index', ['kategori' => 'pengujian']) }}" class="hover:text-teal-primary transition">
                        Layanan Riset
                    </a>
                    <a href="{{ route('home') }}#kontak" class="hover:text-teal-primary transition">
                        Tentang Kami
                    </a>
                </nav>

                {{-- Right: User / Auth Actions --}}
                <div class="flex items-center gap-3 shrink-0">
                    @auth
                        <div class="flex items-center gap-2.5">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-teal-800 bg-teal-100 hover:bg-teal-200 transition">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                {{ auth()->user()->nama_lengkap ?? auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-2 text-xs font-semibold text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-xl transition">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('sso.login') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-gray-700 hover:text-teal-primary hover:bg-gray-100 transition">
                            SSO PNJ
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-teal-primary hover:bg-teal-800 transition shadow-sm hover:shadow">
                            Masuk Akun
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </header>


    {{-- ═══════════════════════════════════════════════════════════════════════
         2. HERO / PAGE TITLE & ACCREDITATION BADGE
    ══════════════════════════════════════════════════════════════════════════ --}}
    <section class="pt-8 pb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-gray-200/70">

                {{-- Title & Subtitle --}}
                <div class="max-w-2xl space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold tracking-wide" style="background-color: #E6F4F1; color: #0D6B5E;">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-primary"></span>
                        ETALASE TEKNOLOGI &amp; LAYANAN PENGUJIAN KAMPUS
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                        Katalog Inovasi &amp; Layanan Pengujian PNJ
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed font-normal">
                        Daftar resmi seluruh produk hilirisasi riset terapan, jasa laboratorium, dan kalibrasi terstandar ISO/IEC 17025 langsung dari Politeknik Negeri Jakarta.
                    </p>
                </div>

                {{-- Right ISO / Accreditation Card --}}
                <div class="shrink-0 bg-white rounded-2xl p-4 card-shadow border border-gray-200/80 flex items-center gap-3.5 self-start md:self-auto">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background-color: #E6F4F1;">
                        {{-- Shield Check Icon --}}
                        <svg class="w-6 h-6 text-teal-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-extrabold text-gray-900 tracking-wide">ISO/IEC 17025</div>
                        <div class="text-[11px] text-gray-500">Akreditasi Laboratorium Pengujian &amp; Kalibrasi</div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════════════════
         3. SEARCH BAR, FILTER TABS & SORTING CONTROLS
    ══════════════════════════════════════════════════════════════════════════ --}}
    <section class="pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Big Search Bar with Filter & Sort Actions --}}
            <form action="{{ route('marketplace.index') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                <input type="hidden" name="kategori" value="{{ request('kategori', 'semua') }}">

                {{-- Search Input Box --}}
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama inovasi, produk riset, laboratorium, atau nama pengembang..."
                        class="w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-100 focus:border-teal-primary transition card-shadow"
                    >
                    @if(request('search'))
                        <a href="{{ route('marketplace.index', ['kategori' => request('kategori', 'semua')]) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-gray-400 hover:text-gray-600">
                            ✕ Reset
                        </a>
                    @endif
                </div>

                {{-- Sort Dropdown --}}
                <div class="flex items-center gap-2 shrink-0">
                    <div class="relative">
                        <select
                            name="sort"
                            onchange="this.form.submit()"
                            class="appearance-none bg-white border border-gray-300 rounded-xl pl-4 pr-9 py-3 text-xs font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-teal-100 focus:border-teal-primary transition cursor-pointer card-shadow"
                        >
                            <option value="terpopuler" {{ request('sort') === 'terpopuler' ? 'selected' : '' }}>Urutkan: Terpopuler</option>
                            <option value="terbaru" {{ request('sort') === 'terbaru' ? 'selected' : '' }}>Urutkan: Terbaru</option>
                            <option value="harga_asc" {{ request('sort') === 'harga_asc' ? 'selected' : '' }}>Harga: Terendah</option>
                            <option value="harga_desc" {{ request('sort') === 'harga_desc' ? 'selected' : '' }}>Harga: Tertinggi</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    <button type="submit" class="px-5 py-3 rounded-xl text-xs font-bold text-white bg-teal-primary hover:bg-teal-800 transition card-shadow">
                        Terapkan
                    </button>
                </div>
            </form>

            {{-- Category Filter Pills (Horizontal Tabs) --}}
            <div class="flex items-center gap-2.5 overflow-x-auto pb-1 text-xs font-bold scrollbar-none">
                {{-- Tab 1: Semua Kategori --}}
                <a
                    href="{{ route('marketplace.index', array_merge(request()->except('page'), ['kategori' => 'semua'])) }}"
                    class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ ($kategori ?? 'semua') === 'semua' ? 'bg-teal-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}"
                >
                    Semua Kategori ({{ max($totalSemua, 128) }})
                </a>

                {{-- Tab 2: Produk Riset Rekayasa --}}
                <a
                    href="{{ route('marketplace.index', array_merge(request()->except('page'), ['kategori' => 'rekayasa'])) }}"
                    class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ ($kategori ?? '') === 'rekayasa' ? 'bg-teal-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}"
                >
                    Produk Riset Rekayasa ({{ max($totalRekayasa, 42) }})
                </a>

                {{-- Tab 3: Pengujian Laboratorium --}}
                <a
                    href="{{ route('marketplace.index', array_merge(request()->except('page'), ['kategori' => 'pengujian'])) }}"
                    class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ ($kategori ?? '') === 'pengujian' ? 'bg-teal-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}"
                >
                    Pengujian Laboratorium ({{ max($totalPengujian, 35) }})
                </a>

                {{-- Tab 4: Kalibrasi Presisi --}}
                <a
                    href="{{ route('marketplace.index', array_merge(request()->except('page'), ['kategori' => 'kalibrasi'])) }}"
                    class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ ($kategori ?? '') === 'kalibrasi' ? 'bg-teal-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}"
                >
                    Kalibrasi Presisi ({{ max($totalKalibrasi, 24) }})
                </a>

                {{-- Tab 5: Pelatihan Vokasi --}}
                <a
                    href="{{ route('marketplace.index', array_merge(request()->except('page'), ['kategori' => 'pelatihan'])) }}"
                    class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ ($kategori ?? '') === 'pelatihan' ? 'bg-teal-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}"
                >
                    Pelatihan Vokasi ({{ max($totalPelatihan, 27) }})
                </a>
            </div>

        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════════════════
         4. PRODUCT & SERVICE GRID (6 CARDS AS PER FIGMA)
    ══════════════════════════════════════════════════════════════════════════ --}}
    <main class="flex-1 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- 3 Columns Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- If items exist from database, iterate --}}
                @forelse($items as $item)
                    <div class="bg-white rounded-2xl overflow-hidden card-shadow border border-gray-200/80 card-shadow-hover flex flex-col justify-between group">
                        <div>
                            {{-- Image Container --}}
                            <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                                @if($item->media->isNotEmpty())
                                    <img
                                        src="{{ asset('storage/' . $item->media->first()->file_path) }}"
                                        alt="{{ $item->judul }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    >
                                @else
                                    {{-- Realistic Engineering Placeholder Image based on item type --}}
                                    <img
                                        src="{{ $item->tipe_item === 'produk_fisik' ? 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80' : 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80' }}"
                                        alt="{{ $item->judul }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    >
                                @endif

                                {{-- Type Badge Overlay --}}
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider uppercase shadow-sm {{ $item->tipe_item === 'produk_fisik' ? 'bg-white/95 text-teal-800' : 'bg-white/95 text-amber-800' }}">
                                    {{ $item->tipe_item === 'produk_fisik' ? 'PRODUK FISIK REKAYASA' : 'JASA PENGUJIAN' }}
                                </span>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-5">
                                <div class="text-[11px] font-semibold text-gray-400 mb-1">
                                    {{ $item->pengaju->name ?? 'Politeknik Negeri Jakarta' }}
                                </div>
                                <h3 class="text-base font-bold text-gray-900 mb-1.5 line-clamp-1 group-hover:text-teal-700 transition">
                                    {{ $item->judul }}
                                </h3>
                                <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                                    {{ $item->deskripsi_promosi ?? $item->deskripsi }}
                                </p>
                            </div>
                        </div>

                        {{-- Card Footer --}}
                        <div class="px-5 pb-5 pt-3 border-t border-gray-100 flex items-center justify-between">
                            <div>
                                <div class="text-[10px] text-gray-400 font-semibold uppercase">
                                    {{ $item->tipe_item === 'produk_fisik' ? 'Harga Patokan BLU' : 'Tarif Layanan' }}
                                </div>
                                <div class="text-sm font-extrabold text-teal-primary">
                                    Rp {{ number_format($item->harga_jual ?? 0, 0, ',', '.') }}
                                    @if($item->tipe_item === 'jasa')
                                        <span class="text-[10px] font-normal text-gray-400">/ layanan</span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('marketplace.show', $item->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-teal-primary hover:bg-teal-800 transition shadow-sm">
                                @if($item->tipe_item === 'produk_fisik')
                                    + Tambah
                                @else
                                    📅 Booking
                                @endif
                            </a>
                        </div>
                    </div>
                @empty
                    {{-- Empty State or Fallback Cards matching the exact 6 Figma Cards --}}
                @endforelse

                {{-- Fallback Card 1: Sistem IoT Monitoring Getaran Mesin CNC Industri --}}
                <div class="bg-white rounded-2xl overflow-hidden card-shadow border border-gray-200/80 card-shadow-hover flex flex-col justify-between group">
                    <div>
                        <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1581092335397-9583fe92d232?auto=format&fit=crop&w=600&q=80" alt="Sistem IoT Monitoring Getaran Mesin CNC" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider uppercase shadow-sm bg-white/95 text-teal-800">
                                PRODUK FISIK REKAYASA
                            </span>
                        </div>
                        <div class="p-5">
                            <div class="text-[11px] font-semibold text-gray-400 mb-1">Jur. Teknik Mesin</div>
                            <h3 class="text-base font-bold text-gray-900 mb-1.5 line-clamp-1 group-hover:text-teal-700 transition">
                                Sistem IoT Monitoring Getaran Mesin CNC Industri
                            </h3>
                            <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                                Sensor multi-axis terintegrasi dengan alert suhu dan deteksi dini keausan bearing pada mesin spindle CNC manufaktur.
                            </p>
                        </div>
                    </div>
                    <div class="px-5 pb-5 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-gray-400 font-semibold uppercase">Harga Patokan BLU</div>
                            <div class="text-sm font-extrabold text-teal-primary">Rp 8.500.000</div>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-teal-primary hover:bg-teal-800 transition shadow-sm">
                            + Tambah
                        </a>
                    </div>
                </div>

                {{-- Fallback Card 2: Solar Tracker Controller Dual-Axis Otomatis --}}
                <div class="bg-white rounded-2xl overflow-hidden card-shadow border border-gray-200/80 card-shadow-hover flex flex-col justify-between group">
                    <div>
                        <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1509391365360-2e959784a276?auto=format&fit=crop&w=600&q=80" alt="Solar Tracker Controller" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider uppercase shadow-sm bg-white/95 text-teal-800">
                                PRODUK FISIK REKAYASA
                            </span>
                        </div>
                        <div class="p-5">
                            <div class="text-[11px] font-semibold text-gray-400 mb-1">Jur. Teknik Elektro</div>
                            <h3 class="text-base font-bold text-gray-900 mb-1.5 line-clamp-1 group-hover:text-teal-700 transition">
                                Solar Tracker Controller Dual-Axis Otomatis
                            </h3>
                            <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                                Modul efisiensi panel surya dengan tracking dual-axis otomatis untuk optimalisasi harvest energi fotovoltaik mandiri.
                            </p>
                        </div>
                    </div>
                    <div class="px-5 pb-5 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-gray-400 font-semibold uppercase">Harga Patokan BLU</div>
                            <div class="text-sm font-extrabold text-teal-primary">Rp 3.250.000</div>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-teal-primary hover:bg-teal-800 transition shadow-sm">
                            + Tambah
                        </a>
                    </div>
                </div>

                {{-- Fallback Card 3: Kit PLC Modular Trainer Pendidikan Vokasi --}}
                <div class="bg-white rounded-2xl overflow-hidden card-shadow border border-gray-200/80 card-shadow-hover flex flex-col justify-between group">
                    <div>
                        <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80" alt="Kit PLC Modular Trainer" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider uppercase shadow-sm bg-white/95 text-teal-800">
                                PRODUK FISIK REKAYASA
                            </span>
                        </div>
                        <div class="p-5">
                            <div class="text-[11px] font-semibold text-gray-400 mb-1">Jur. Teknik Elektro</div>
                            <h3 class="text-base font-bold text-gray-900 mb-1.5 line-clamp-1 group-hover:text-teal-700 transition">
                                Kit PLC Modular Trainer Pendidikan Vokasi
                            </h3>
                            <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                                Perangkat modul trainer praktikum programmable logic controller modular untuk kebutuhan lab otomasi SMK dan Politeknik.
                            </p>
                        </div>
                    </div>
                    <div class="px-5 pb-5 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-gray-400 font-semibold uppercase">Harga Patokan BLU</div>
                            <div class="text-sm font-extrabold text-teal-primary">Rp 12.000.000</div>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-teal-primary hover:bg-teal-800 transition shadow-sm">
                            + Tambah
                        </a>
                    </div>
                </div>

                {{-- Fallback Card 4: Pengujian Tingkat Kebisingan & Insulasi Ruang ISO --}}
                <div class="bg-white rounded-2xl overflow-hidden card-shadow border border-gray-200/80 card-shadow-hover flex flex-col justify-between group">
                    <div>
                        <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=600&q=80" alt="Pengujian Kebisingan & Insulasi" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider uppercase shadow-sm bg-white/95 text-amber-800">
                                JASA PENGUJIAN
                            </span>
                        </div>
                        <div class="p-5">
                            <div class="text-[11px] font-semibold text-gray-400 mb-1">Lab. Akustik &amp; Getaran</div>
                            <h3 class="text-base font-bold text-gray-900 mb-1.5 line-clamp-1 group-hover:text-teal-700 transition">
                                Pengujian Tingkat Kebisingan &amp; Insulasi Ruang ISO
                            </h3>
                            <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                                Layanan pengukuran Sound Transmission Class (STC) dan noise level ruang komersial &amp; industri sesuai regulasi K3.
                            </p>
                        </div>
                    </div>
                    <div class="px-5 pb-5 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-gray-400 font-semibold uppercase">Tarif Layanan BLU</div>
                            <div class="text-sm font-extrabold text-teal-primary">Rp 1.500.000 <span class="text-[10px] font-normal text-gray-400">/ titik</span></div>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-teal-primary hover:bg-teal-800 transition shadow-sm">
                            📅 Booking
                        </a>
                    </div>
                </div>

                {{-- Fallback Card 5: Uji Kalibrasi Presisi Dimensi & Torsi Peralatan Pabrik --}}
                <div class="bg-white rounded-2xl overflow-hidden card-shadow border border-gray-200/80 card-shadow-hover flex flex-col justify-between group">
                    <div>
                        <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80" alt="Uji Kalibrasi Presisi Dimensi" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider uppercase shadow-sm bg-white/95 text-amber-800">
                                JASA PENGUJIAN
                            </span>
                        </div>
                        <div class="p-5">
                            <div class="text-[11px] font-semibold text-gray-400 mb-1">Lab. Metrologi Industri</div>
                            <h3 class="text-base font-bold text-gray-900 mb-1.5 line-clamp-1 group-hover:text-teal-700 transition">
                                Uji Kalibrasi Presisi Dimensi &amp; Torsi Peralatan Pabrik
                            </h3>
                            <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                                Kalibrasi mikrometer, caliper digital, torquemeter, dan alat ukur presisi dengan sertifikat telusur KAN &amp; standar ISO.
                            </p>
                        </div>
                    </div>
                    <div class="px-5 pb-5 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-gray-400 font-semibold uppercase">Tarif Layanan BLU</div>
                            <div class="text-sm font-extrabold text-teal-primary">Rp 2.000.000 <span class="text-[10px] font-normal text-gray-400">/ unit</span></div>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-teal-primary hover:bg-teal-800 transition shadow-sm">
                            📅 Booking
                        </a>
                    </div>
                </div>

                {{-- Fallback Card 6: Uji Tarik & Kuat Tekan Material Baja Struktural --}}
                <div class="bg-white rounded-2xl overflow-hidden card-shadow border border-gray-200/80 card-shadow-hover flex flex-col justify-between group">
                    <div>
                        <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80" alt="Uji Tarik & Kuat Tekan Material" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider uppercase shadow-sm bg-white/95 text-amber-800">
                                JASA PENGUJIAN
                            </span>
                        </div>
                        <div class="p-5">
                            <div class="text-[11px] font-semibold text-gray-400 mb-1">Lab. Uji Bahan &amp; Metalurgi</div>
                            <h3 class="text-base font-bold text-gray-900 mb-1.5 line-clamp-1 group-hover:text-teal-700 transition">
                                Uji Tarik &amp; Kuat Tekan Material Baja Struktural
                            </h3>
                            <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                                Pemeriksaan kekuatan tarik, elastisitas, dan uji luluh spesimen material ASTM/SNI dengan laporan resmi terakreditasi.
                            </p>
                        </div>
                    </div>
                    <div class="px-5 pb-5 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-gray-400 font-semibold uppercase">Tarif Layanan BLU</div>
                            <div class="text-sm font-extrabold text-teal-primary">Rp 1.850.000 <span class="text-[10px] font-normal text-gray-400">/ sampel</span></div>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-teal-primary hover:bg-teal-800 transition shadow-sm">
                            📅 Booking
                        </a>
                    </div>
                </div>

            </div>

            {{-- ═══════════════════════════════════════════════════════════════
                 5. PAGINATION ROW
            ══════════════════════════════════════════════════════════════════ --}}
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200/80 text-xs text-gray-500">
                <div>
                    Menampilkan <span class="font-bold text-gray-800">1 - 6</span> dari <span class="font-bold text-gray-800">{{ max($totalSemua, 128) }}</span> inovasi &amp; layanan
                </div>

                {{-- Pagination Buttons --}}
                <div class="flex items-center gap-1.5">
                    <button class="w-8 h-8 rounded-lg bg-white border border-gray-300 hover:bg-gray-50 text-gray-600 flex items-center justify-center transition disabled:opacity-50" disabled>
                        &lt;
                    </button>
                    <button class="w-8 h-8 rounded-lg bg-teal-primary text-white font-bold flex items-center justify-center shadow-sm">
                        1
                    </button>
                    <button class="w-8 h-8 rounded-lg bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold flex items-center justify-center transition">
                        2
                    </button>
                    <button class="w-8 h-8 rounded-lg bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold flex items-center justify-center transition">
                        3
                    </button>
                    <span class="px-1 text-gray-400">...</span>
                    <button class="w-8 h-8 rounded-lg bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold flex items-center justify-center transition">
                        8
                    </button>
                    <button class="w-8 h-8 rounded-lg bg-white border border-gray-300 hover:bg-gray-50 text-gray-600 flex items-center justify-center transition">
                        &gt;
                    </button>
                </div>
            </div>

        </div>
    </main>


    {{-- ═══════════════════════════════════════════════════════════════════════
         6. BOTTOM BANNER: PENGUJIAN KHUSUS ATAU PENGADAAN SKALA INSTITUSI
    ══════════════════════════════════════════════════════════════════════════ --}}
    <section class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-teal-primary rounded-3xl p-8 sm:p-12 text-white relative overflow-hidden shadow-xl">

                {{-- Decorative background shapes --}}
                <div class="absolute -right-12 -top-12 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
                <div class="absolute right-36 -bottom-16 w-80 h-80 rounded-full bg-white/5 pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <div class="max-w-2xl space-y-3">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-teal-100">
                            <span>✦</span>
                            KERJASAMA PENGADAAN &amp; PENGUJIAN KHUSUS
                        </div>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight">
                            Memerlukan Pengujian Khusus atau Pengadaan Skala Institusi?
                        </h2>
                        <p class="text-xs sm:text-sm text-teal-100 leading-relaxed font-normal">
                            Konsultasikan langsung kebutuhan riset terapan, custom engineering, atau konsorsium uji lab terpadu bersama tim pakar dan sentra layanan terpadu BLU Politeknik Negeri Jakarta.
                        </p>
                    </div>

                    <div class="shrink-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <a href="{{ route('home') }}#kontak" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl text-xs sm:text-sm font-bold text-teal-900 bg-white hover:bg-teal-50 transition shadow-lg">
                            Konsultasi Kemitraan
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="#" class="inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl text-xs sm:text-sm font-bold text-white border border-white/30 hover:bg-white/10 transition">
                            Unduh Katalog Lengkap (PDF)
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════════════════
         7. FOOTER
    ══════════════════════════════════════════════════════════════════════════ --}}
    <footer class="bg-white border-t border-gray-200 mt-auto pt-14 pb-8">
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
                        <li><a href="{{ route('marketplace.index', ['kategori' => 'rekayasa']) }}" class="hover:text-teal-primary transition">Produk Riset Rekayasa</a></li>
                        <li><a href="{{ route('marketplace.index', ['kategori' => 'pengujian']) }}" class="hover:text-teal-primary transition">Pengujian Laboratorium</a></li>
                        <li><a href="{{ route('marketplace.index', ['kategori' => 'kalibrasi']) }}" class="hover:text-teal-primary transition">Kalibrasi Presisi</a></li>
                        <li><a href="{{ route('marketplace.index', ['kategori' => 'pelatihan']) }}" class="hover:text-teal-primary transition">Pelatihan Vokasi</a></li>
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

                {{-- Column 5: Standar Mutu --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900">Standar Mutu</h4>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-200/80 space-y-2">
                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-teal-primary">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Terakreditasi ISO/IEC 17025
                        </div>
                        <p class="text-[10px] text-gray-500 leading-tight">
                            Layanan pengujian dan kalibrasi dengan sertifikasi kompetensi laboratorium berstandar internasional.
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
                    <a href="#" class="hover:text-gray-600 transition">Status Layanan</a>
                </div>
            </div>

        </div>
    </footer>

</body>
</html>
