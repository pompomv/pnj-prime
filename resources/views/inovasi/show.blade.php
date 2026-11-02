@extends('layouts.app')

@section('title', $inovasi->judul . ' — Detail Inovasi')

@section('content')

<div class="max-w-5xl mx-auto">
    {{-- Top navigation --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-blue-800 hover:underline inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $inovasi->judul }}</h1>
            <p class="text-gray-500 text-sm mt-0.5">
                Diajukan oleh <span class="font-medium text-gray-700">{{ $inovasi->pengaju?->name }}</span> ({{ ucfirst($inovasi->pengaju?->role) }}) &bull; {{ $inovasi->created_at->format('d M Y, H:i') }}
            </p>
        </div>

        <div class="flex items-center space-x-2">
            @if($bisaEdit)
                <a href="{{ route('inovasi.edit', $inovasi->id) }}" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-medium text-sm rounded-xl shadow-sm transition">
                    Edit Pengajuan
                </a>
                <form action="{{ route('inovasi.destroy', $inovasi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan inovasi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium text-sm rounded-xl shadow-sm transition">
                        Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Status Timeline Bar --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm mb-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Status &amp; Alur Hilirisasi</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Tahap 1 --}}
            <div class="p-4 rounded-xl border {{ $inovasi->status_kurasi === 'lolos_p3m' ? 'bg-green-50 border-green-200' : ($inovasi->status_kurasi === 'ditolak' ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200') }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Tahap 1: Kurasi P3M</span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $inovasi->status_kurasi === 'lolos_p3m' ? 'bg-green-200 text-green-800' : ($inovasi->status_kurasi === 'ditolak' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                        {{ $inovasi->status_label }}
                    </span>
                </div>
                <p class="text-sm font-semibold mt-2 text-gray-800">
                    @if($inovasi->status_kurasi === 'lolos_p3m')
                        Telah Disetujui
                    @elseif($inovasi->status_kurasi === 'revisi')
                        Perlu Perbaikan
                    @elseif($inovasi->status_kurasi === 'ditolak')
                        Ditolak
                    @else
                        Menunggu Review
                    @endif
                </p>
                @if($inovasi->dikurasi_oleh)
                    <p class="text-xs text-gray-500 mt-1">Oleh: {{ $inovasi->kurator?->name }} ({{ $inovasi->dikurasi_pada?->format('d M Y') }})</p>
                @endif
                @if($inovasi->catatan_revisi)
                    <div class="mt-2 text-xs bg-white/70 p-2 rounded-lg text-gray-700">
                        <strong>Catatan:</strong> {{ $inovasi->catatan_revisi }}
                    </div>
                @endif
            </div>

            {{-- Tahap 2 --}}
            <div class="p-4 rounded-xl border {{ !empty($inovasi->harga_jual) ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Tahap 2: Harga Pimpinan</span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ !empty($inovasi->harga_jual) ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-600' }}">
                        {{ !empty($inovasi->harga_jual) ? 'Ditetapkan' : 'Belum Ada' }}
                    </span>
                </div>
                <div class="mt-2">
                    <p class="text-xs text-gray-500">HPP: <span class="font-semibold text-gray-800">{{ $inovasi->hpp ? $inovasi->hpp_format : '—' }}</span></p>
                    <p class="text-sm font-bold text-gray-900 mt-0.5">Harga Jual: {{ $inovasi->harga_jual ? $inovasi->harga_format : '—' }}</p>
                </div>
                @if($inovasi->harga_ditetapkan_oleh)
                    <p class="text-xs text-gray-500 mt-1">Oleh: {{ $inovasi->penetapHarga?->name }} ({{ $inovasi->harga_ditetapkan_pada?->format('d M Y') }})</p>
                @endif
            </div>

            {{-- Tahap 3 --}}
            <div class="p-4 rounded-xl border {{ $inovasi->is_published ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200' }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Tahap 3: Etalase RTPU</span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $inovasi->is_published ? 'bg-blue-200 text-blue-800' : 'bg-gray-200 text-gray-600' }}">
                        {{ $inovasi->is_published ? 'Aktif di Etalase' : 'Belum Publik' }}
                    </span>
                </div>
                <p class="text-sm font-semibold mt-2 text-gray-800">
                    {{ $inovasi->is_published ? 'Dapat Dilihat Publik' : 'Draft Internal' }}
                </p>
                @if($inovasi->dipublikasi_oleh)
                    <p class="text-xs text-gray-500 mt-1">Oleh: {{ $inovasi->publisher?->name }} ({{ $inovasi->dipublikasi_pada?->format('d M Y') }})</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Detail Card --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            {{-- Deskripsi --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-3 text-base">Deskripsi &amp; Spesifikasi</h3>
                <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $inovasi->deskripsi }}
                </div>

                @if($inovasi->deskripsi_promosi)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h4 class="font-bold text-blue-900 text-sm mb-2 flex items-center">
                            <span class="mr-1.5">📢</span> Narasi Promosi Etalase (oleh RTPU)
                        </h4>
                        <p class="text-xs text-gray-600 italic bg-blue-50/60 p-3 rounded-xl border border-blue-100">
                            "{{ $inovasi->deskripsi_promosi }}"
                        </p>
                    </div>
                @endif
            </div>

            {{-- Dokumen & Tautan --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-3 text-base">Dokumen Pendukung &amp; Foto</h3>
                <div class="space-y-3">
                    @if($inovasi->url_dokumen_pendukung)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-200 text-sm">
                            <div class="flex items-center space-x-2">
                                <span class="text-red-600 font-bold text-xs uppercase px-2 py-0.5 bg-red-100 rounded">DOC</span>
                                <span class="font-medium text-gray-800">Dokumen / Proposal Riset</span>
                            </div>
                            <a href="{{ $inovasi->url_dokumen_pendukung }}" target="_blank" rel="noopener noreferrer" class="text-xs text-blue-700 font-semibold hover:underline inline-flex items-center">
                                Buka Tautan &rarr;
                            </a>
                        </div>
                    @else
                        <p class="text-xs text-gray-400 italic">Tidak ada dokumen pendukung yang dilampirkan.</p>
                    @endif

                    @if($inovasi->url_foto_prototipe)
                        <div class="mt-4">
                            <p class="text-xs font-semibold text-gray-500 mb-2">Foto Prototipe:</p>
                            <img src="{{ $inovasi->url_foto_prototipe }}" alt="{{ $inovasi->judul }}" class="rounded-xl border border-gray-200 max-h-80 w-full object-cover">
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4 text-sm">Spesifikasi Item</h3>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-xs text-gray-500">Tipe Inovasi</dt>
                        <dd class="font-semibold text-gray-800 mt-0.5">
                            <span class="px-2.5 py-0.5 rounded-full text-xs {{ $inovasi->tipe_item === 'produk_fisik' ? 'bg-indigo-100 text-indigo-700' : 'bg-teal-100 text-teal-700' }}">
                                {{ $inovasi->tipe_label }}
                            </span>
                        </dd>
                    </div>

                    @if($inovasi->tipe_item === 'produk_fisik')
                    <div>
                        <dt class="text-xs text-gray-500">Stok &amp; Satuan</dt>
                        <dd class="font-semibold text-gray-800 mt-0.5">{{ $inovasi->stok ?? 0 }} {{ $inovasi->satuan }}</dd>
                    </div>
                    @else
                    <div>
                        <dt class="text-xs text-gray-500">Satuan Layanan</dt>
                        <dd class="font-semibold text-gray-800 mt-0.5">Per {{ $inovasi->satuan ?? 'sesi' }}</dd>
                    </div>
                    @endif

                    <div class="pt-3 border-t border-gray-100">
                        <dt class="text-xs text-gray-500">HPP (Harga Pokok Penjualan)</dt>
                        <dd class="font-semibold text-gray-700 mt-0.5">{{ $inovasi->hpp ? $inovasi->hpp_format : 'Belum ditetapkan' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">Harga Jual Resmi</dt>
                        <dd class="text-lg font-bold text-blue-800 mt-0.5">{{ $inovasi->harga_jual ? $inovasi->harga_format : 'Belum ditetapkan' }}</dd>
                    </div>

                    <div class="pt-3 border-t border-gray-100">
                        <dt class="text-xs text-gray-500">Pengaju Inovasi</dt>
                        <dd class="font-medium text-gray-800 mt-0.5">{{ $inovasi->pengaju?->name }}</dd>
                        <dd class="text-xs text-gray-500">{{ $inovasi->pengaju?->email }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>

@endsection

