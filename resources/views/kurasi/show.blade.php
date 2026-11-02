@extends('layouts.app')

@section('title', 'Validasi Kurasi — PNJ PRIME')

@section('content')

<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-blue-800 hover:underline inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Antrian Kurasi P3M
        </a>
        <div class="flex flex-wrap items-center justify-between gap-4 mt-2">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Validasi &amp; Kurasi Inovasi Riset</h1>
                <p class="text-gray-500 text-sm mt-0.5">
                    Evaluasi kesiapan teknologi, kelengkapan berkas, dan kelayakan hilirisasi inovasi.
                </p>
            </div>
            <div>
                @php
                    $statusBadge = match($inovasi->status_kurasi) {
                        'menunggu_validasi' => 'bg-yellow-100 text-yellow-800',
                        'revisi'            => 'bg-orange-100 text-orange-800',
                        'ditolak'           => 'bg-red-100 text-red-800',
                        'lolos_p3m'         => 'bg-green-100 text-green-800',
                        default             => 'bg-gray-100 text-gray-800',
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusBadge }}">
                    Status: {{ $inovasi->status_label }}
                </span>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
            <ul class="list-disc list-inside space-y-0.5 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Detail Konten Inovasi --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $inovasi->judul }}</h2>
                <div class="flex items-center space-x-3 text-xs text-gray-500 mb-4 pb-4 border-b border-gray-100">
                    <span class="px-2.5 py-0.5 rounded-full font-medium {{ $inovasi->tipe_item === 'produk_fisik' ? 'bg-indigo-100 text-indigo-700' : 'bg-teal-100 text-teal-700' }}">
                        {{ $inovasi->tipe_label }}
                    </span>
                    <span>Diajukan: {{ $inovasi->created_at->format('d M Y, H:i') }}</span>
                    @if($inovasi->tipe_item === 'produk_fisik')
                        <span>Stok: {{ $inovasi->stok ?? 0 }} {{ $inovasi->satuan }}</span>
                    @endif
                </div>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Deskripsi Inovasi</h3>
                        <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $inovasi->deskripsi }}</p>
                    </div>

                    @if($inovasi->url_dokumen_pendukung)
                    <div class="pt-4 border-t border-gray-100">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Dokumen Pendukung Riset</h3>
                        <a href="{{ $inovasi->url_dokumen_pendukung }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-blue-700 hover:bg-gray-100 transition">
                            <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                            </svg>
                            Tinjau Dokumen / Proposal (PDF / Drive) &rarr;
                        </a>
                    </div>
                    @endif

                    @if($inovasi->url_foto_prototipe)
                    <div class="pt-4 border-t border-gray-100">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Foto Prototipe / Visual</h3>
                        <img src="{{ $inovasi->url_foto_prototipe }}" alt="{{ $inovasi->judul }}" class="rounded-xl border border-gray-200 max-h-72 w-full object-cover">
                    </div>
                    @endif
                </div>
            </div>

            {{-- Identitas Pengaju --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 mb-3">Identitas Pengaju Inovasi</h3>
                <div class="flex items-center space-x-4">
                    <img src="{{ $inovasi->pengaju?->avatar_url }}" alt="avatar" class="w-12 h-12 rounded-xl object-cover">
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ $inovasi->pengaju?->name }}</p>
                        <p class="text-xs text-gray-500">{{ $inovasi->pengaju?->email }} &bull; Role: {{ ucfirst($inovasi->pengaju?->role) }}</p>
                        @if($inovasi->pengaju?->sso_id)
                            <p class="text-xs text-gray-400 mt-0.5">SSO ID: {{ $inovasi->pengaju?->sso_id }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Aksi Kurator P3M --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center">
                    <span class="mr-2">⚖️</span> Keputusan Kurasi P3M
                </h3>

                {{-- Opsi 1: Setujui --}}
                <div class="p-4 bg-green-50 rounded-xl border border-green-200 mb-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-green-900 mb-1">1. Loloskan Inovasi</h4>
                    <p class="text-xs text-green-700 mb-3">Inovasi memenuhi syarat dan diteruskan ke Pimpinan untuk penetapan HPP &amp; harga jual.</p>
                    <form action="{{ route('kurasi.setujui', $inovasi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyatakan inovasi ini LOLOS KURASI P3M?')">
                        @csrf
                        <button type="submit" class="w-full py-2.5 px-4 bg-green-600 hover:bg-green-700 text-white font-semibold text-xs rounded-xl transition">
                            ✓ Nyatakan Lolos P3M
                        </button>
                    </form>
                </div>

                {{-- Opsi 2: Minta Revisi --}}
                <div class="p-4 bg-orange-50 rounded-xl border border-orange-200 mb-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-orange-900 mb-1">2. Minta Revisi</h4>
                    <p class="text-xs text-orange-700 mb-3">Berikan catatan hal-hal yang perlu diperbaiki oleh pengaju.</p>
                    <form action="{{ route('kurasi.revisi', $inovasi->id) }}" method="POST">
                        @csrf
                        <textarea
                            name="catatan_revisi"
                            rows="3"
                            placeholder="Tuliskan catatan perbaikan untuk pengaju..."
                            required
                            class="w-full px-3 py-2 text-xs border border-orange-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 mb-2 bg-white"
                        ></textarea>
                        <button type="submit" class="w-full py-2 px-4 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs rounded-xl transition">
                            Kirim Permintaan Revisi
                        </button>
                    </form>
                </div>

                {{-- Opsi 3: Tolak --}}
                <div class="p-4 bg-red-50 rounded-xl border border-red-200">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-red-900 mb-1">3. Tolak Inovasi</h4>
                    <p class="text-xs text-red-700 mb-3">Inovasi tidak memenuhi kriteria hilirisasi riset kampus.</p>
                    <form action="{{ route('kurasi.tolak', $inovasi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK inovasi ini?')">
                        @csrf
                        <textarea
                            name="catatan_revisi"
                            rows="2"
                            placeholder="Alasan penolakan..."
                            required
                            class="w-full px-3 py-2 text-xs border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 mb-2 bg-white"
                        ></textarea>
                        <button type="submit" class="w-full py-2 px-4 bg-red-600 hover:bg-red-700 text-white font-semibold text-xs rounded-xl transition">
                            Tolak Inovasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

