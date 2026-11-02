@extends('layouts.app')

@section('title', $inovasi->judul . ' — Katalog Riset PNJ PRIME')

@section('content')

<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('marketplace.index') }}" class="text-sm font-medium text-blue-800 hover:underline inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Katalog Etalase
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Kolom Kiri: Visual & Deskripsi --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Image Box --}}
            <div class="bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-sm">
                @if($inovasi->url_foto_prototipe)
                    <img src="{{ $inovasi->url_foto_prototipe }}" alt="{{ $inovasi->judul }}" class="w-full max-h-96 object-cover">
                @else
                    <div class="h-64 bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center text-center p-6">
                        <div>
                            <span class="text-5xl">🔬</span>
                            <p class="text-sm font-semibold text-gray-700 mt-2">Prototipe Inovasi Riset PNJ</p>
                        </div>
                    </div>
                @endif

                <div class="p-6 sm:p-8">
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $inovasi->tipe_item === 'produk_fisik' ? 'bg-indigo-100 text-indigo-700' : 'bg-teal-100 text-teal-700' }}">
                            {{ $inovasi->tipe_label }}
                        </span>
                        <span class="text-xs text-gray-400">&bull; Dipublikasikan resmi oleh RTPU PNJ</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-snug">
                        {{ $inovasi->judul }}
                    </h1>

                    {{-- Highlight Narasi Promosi --}}
                    @if($inovasi->deskripsi_promosi)
                        <div class="mt-4 p-4 bg-blue-50 border-l-4 border-blue-600 rounded-r-2xl text-blue-900 text-sm italic leading-relaxed">
                            "{{ $inovasi->deskripsi_promosi }}"
                        </div>
                    @endif

                    {{-- Deskripsi Lengkap --}}
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h3 class="font-bold text-gray-900 text-base mb-3">Spesifikasi &amp; Gambaran Inovasi</h3>
                        <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $inovasi->deskripsi }}
                        </div>
                    </div>

                    {{-- Dokumen Pendukung --}}
                    @if($inovasi->url_dokumen_pendukung)
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <h3 class="font-bold text-gray-900 text-base mb-2">Brosur / Datasheet Teknis</h3>
                            <a href="{{ $inovasi->url_dokumen_pendukung }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-blue-800 hover:bg-gray-100 transition">
                                <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                </svg>
                                Unduh Spesifikasi Lengkap (PDF / Berkas) &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Card Harga & Informasi Kemitraan --}}
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-7 shadow-sm sticky top-6">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Harga Resmi Hilirisasi</span>
                <div class="mt-2 flex items-baseline space-x-1">
                    <span class="text-3xl font-extrabold text-blue-900">{{ $inovasi->harga_format }}</span>
                    @if($inovasi->tipe_item === 'jasa')
                        <span class="text-xs text-gray-500 font-medium">/ {{ $inovasi->satuan ?: 'sesi' }}</span>
                    @endif
                </div>

                {{-- Status Ketersediaan --}}
                <div class="mt-4 p-3 bg-gray-50 rounded-2xl text-xs text-gray-600 flex items-center justify-between">
                    <span>Ketersediaan:</span>
                    @if($inovasi->tipe_item === 'produk_fisik')
                        <span class="font-bold text-gray-900">{{ $inovasi->stok ?? 0 }} {{ $inovasi->satuan ?: 'unit' }} siap dipesan</span>
                    @else
                        <span class="font-bold text-teal-700">Tersedia untuk Booking Jadwal</span>
                    @endif
                </div>

                {{-- Info Inovator --}}
                <div class="mt-6 pt-6 border-t border-gray-100 text-xs">
                    <span class="text-gray-400 block mb-1">Inovator / Peneliti:</span>
                    <p class="font-bold text-gray-800 text-sm">{{ $inovasi->pengaju?->name }}</p>
                    <p class="text-gray-500">{{ ucfirst($inovasi->pengaju?->role) }} Politeknik Negeri Jakarta</p>
                </div>

                {{-- Informasi Pemesanan / Kemitraan BLU --}}
                <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-2xl text-xs text-amber-900 space-y-2">
                    <p class="font-bold text-sm flex items-center">
                        <span class="mr-1.5">🤝</span> Layanan Kemitraan Kampus
                    </p>
                    <p class="leading-relaxed">
                        Seluruh transaksi dan pengujian riset disalurkan melalui Rekening Resmi BLU Politeknik Negeri Jakarta.
                    </p>
                    <p class="leading-relaxed text-amber-800">
                        Untuk penawaran kerjasama industri, pengujian massal, atau implementasi prototipe, silakan berkoordinasi dengan Unit Layanan RTPU PNJ.
                    </p>
                </div>

                <div class="mt-6">
                    <a href="mailto:rtpu@pnj.ac.id?subject=Inquiry Kemitraan: {{ urlencode($inovasi->judul) }}"
                       class="w-full block text-center py-3 px-4 bg-blue-800 hover:bg-blue-900 text-white font-semibold text-sm rounded-xl shadow-sm transition">
                        Hubungi Tim RTPU via Email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

