@extends('layouts.app')

@section('title', 'Katalog Etalase Riset & Jasa — PNJ PRIME')

@section('content')

{{-- ─── Hero Section ───────────────────────────────────────────────────────── --}}
<div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-3xl p-8 sm:p-10 mb-8 text-white shadow-lg relative overflow-hidden">
    <div class="relative z-10 max-w-2xl">
        <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider text-blue-200">
            Hilirisasi Riset Kampus PNJ
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-3 tracking-tight">
            Etalase Inovasi &amp; Jasa Pengujian
        </h1>
        <p class="text-blue-100 text-sm sm:text-base mt-2 leading-relaxed">
            Temukan produk riset terapan inovatif karya dosen dan mahasiswa serta fasilitas pengujian laboratorium terakreditasi Politeknik Negeri Jakarta.
        </p>
    </div>
</div>

{{-- ─── Filter & Search Bar ────────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-5 shadow-sm mb-8">
    <form action="{{ route('marketplace.index') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-4">
        {{-- Search Input --}}
        <div class="relative w-full sm:w-96">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari inovasi, alat, atau jasa pengujian..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        {{-- Type Badges --}}
        <div class="flex items-center space-x-2 w-full sm:w-auto overflow-x-auto">
            <a href="{{ route('marketplace.index', array_merge(request()->query(), ['tipe' => null])) }}"
               class="px-4 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap {{ !request('tipe') ? 'bg-blue-800 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua
            </a>
            <a href="{{ route('marketplace.index', array_merge(request()->query(), ['tipe' => 'produk_fisik'])) }}"
               class="px-4 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap {{ request('tipe') === 'produk_fisik' ? 'bg-indigo-700 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Produk Fisik
            </a>
            <a href="{{ route('marketplace.index', array_merge(request()->query(), ['tipe' => 'jasa'])) }}"
               class="px-4 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap {{ request('tipe') === 'jasa' ? 'bg-teal-700 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Jasa &amp; Pengujian
            </a>
        </div>
    </form>
</div>

{{-- ─── Product Grid ───────────────────────────────────────────────────────── --}}
@if($items->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-400">
        <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        <p class="text-base font-semibold text-gray-700">Tidak ada produk atau jasa ditemukan</p>
        <p class="text-xs text-gray-400 mt-1">Coba gunakan kata kunci pencarian yang berbeda atau hapus filter tipe.</p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($items as $item)
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
            <div>
                {{-- Image Thumbnail --}}
                <div class="h-48 bg-gray-100 relative overflow-hidden flex items-center justify-center">
                    @if($item->url_foto_prototipe)
                        <img src="{{ $item->url_foto_prototipe }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-center p-4">
                            <span class="text-3xl">🔬</span>
                            <span class="block text-xs text-gray-400 mt-1">Inovasi Riset PNJ</span>
                        </div>
                    @endif

                    {{-- Badge --}}
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $item->tipe_item === 'produk_fisik' ? 'bg-indigo-600 text-white' : 'bg-teal-600 text-white' }}">
                            {{ $item->tipe_label }}
                        </span>
                    </div>
                </div>

                {{-- Card Content --}}
                <div class="p-5">
                    <p class="text-xs text-gray-400 mb-1">Inovator: {{ $item->pengaju?->name }}</p>
                    <h3 class="font-bold text-gray-900 text-base line-clamp-2 mb-2 hover:text-blue-800 transition">
                        <a href="{{ route('marketplace.show', $item->id) }}">{{ $item->judul }}</a>
                    </h3>
                    <p class="text-xs text-gray-500 line-clamp-3 leading-relaxed">
                        {{ $item->deskripsi_promosi ?: $item->deskripsi }}
                    </p>
                </div>
            </div>

            {{-- Price & CTA --}}
            <div class="p-5 pt-0">
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div>
                        <span class="text-[10px] text-gray-400 uppercase font-semibold block">Harga Resmi BLU</span>
                        <span class="text-base font-extrabold text-blue-900">{{ $item->harga_format }}</span>
                        @if($item->tipe_item === 'jasa')
                            <span class="text-[10px] text-gray-500">/ {{ $item->satuan ?: 'sesi' }}</span>
                        @endif
                    </div>
                    <a href="{{ route('marketplace.show', $item->id) }}" class="px-4 py-2 bg-blue-800 hover:bg-blue-900 text-white text-xs font-semibold rounded-xl transition">
                        Detail Item &rarr;
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $items->links() }}
    </div>
@endif

@endsection

