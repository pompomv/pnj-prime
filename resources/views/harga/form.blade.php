@extends('layouts.app')

@section('title', 'Penetapan HPP & Harga Jual — PNJ PRIME')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-blue-800 hover:underline inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Dashboard Pimpinan
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Penetapan HPP &amp; Harga Pasar</h1>
        <p class="text-gray-500 text-sm mt-0.5">
            Pimpinan mengesahkan Harga Pokok Penjualan (HPP) dan Harga Jual resmi SK Politeknik Negeri Jakarta.
        </p>
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

    {{-- Ringkasan Inovasi --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm mb-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $inovasi->tipe_item === 'produk_fisik' ? 'bg-indigo-100 text-indigo-700' : 'bg-teal-100 text-teal-700' }}">
                    {{ $inovasi->tipe_label }}
                </span>
                <h2 class="text-lg font-bold text-gray-900 mt-1">{{ $inovasi->judul }}</h2>
                <p class="text-xs text-gray-500">Pengaju: {{ $inovasi->pengaju?->name }} &bull; Lolos kurasi P3M pada: {{ $inovasi->dikurasi_pada?->format('d M Y') }}</p>
            </div>
            <div class="text-right">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                    ✓ Lolos P3M
                </span>
            </div>
        </div>

        <p class="text-xs text-gray-600 mt-3 line-clamp-3 leading-relaxed">
            {{ $inovasi->deskripsi }}
        </p>

        @if($inovasi->tipe_item === 'produk_fisik')
            <p class="text-xs text-gray-500 mt-2">Stok: <strong>{{ $inovasi->stok ?? 0 }} {{ $inovasi->satuan }}</strong></p>
        @else
            <p class="text-xs text-gray-500 mt-2">Satuan Layanan: <strong>Per {{ $inovasi->satuan ?? 'sesi' }}</strong></p>
        @endif
    </div>

    {{-- Form Penetapan Harga --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('harga.tetapkan', $inovasi->id) }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="hpp" class="block text-sm font-semibold text-gray-800 mb-1">
                    Harga Pokok Penjualan (HPP) <span class="text-red-500">*</span>
                </label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 font-semibold text-sm">
                        Rp
                    </div>
                    <input
                        type="number"
                        id="hpp"
                        name="hpp"
                        value="{{ old('hpp', $inovasi->hpp ? (int)$inovasi->hpp : '') }}"
                        step="1000"
                        min="0"
                        placeholder="Contoh: 500000"
                        required
                        class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                        oninput="hitungMargin()"
                    >
                </div>
                <span class="text-xs text-gray-400 mt-1 block">Biaya produksi / modal dasar per satuan unit atau sesi pengujian.</span>
            </div>

            <div>
                <label for="harga_jual" class="block text-sm font-semibold text-gray-800 mb-1">
                    Harga Jual Resmi / Harga Pasar <span class="text-red-500">*</span>
                </label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 font-semibold text-sm">
                        Rp
                    </div>
                    <input
                        type="number"
                        id="harga_jual"
                        name="harga_jual"
                        value="{{ old('harga_jual', $inovasi->harga_jual ? (int)$inovasi->harga_jual : '') }}"
                        step="1000"
                        min="0"
                        placeholder="Contoh: 750000"
                        required
                        class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                        oninput="hitungMargin()"
                    >
                </div>
                <span class="text-xs text-gray-400 mt-1 block">Harga final yang akan ditagihkan ke pihak masyarakat / industri di etalase PNJ PRIME.</span>
            </div>

            {{-- Estimasi Margin / Keuntungan BLU --}}
            <div id="marginBox" class="p-4 bg-gray-50 border border-gray-200 rounded-xl text-xs space-y-1">
                <span class="font-bold text-gray-700">Analisis Margin Hilirisasi:</span>
                <p id="marginNilai" class="text-gray-600">Masukkan nilai HPP dan Harga Jual untuk melihat estimasi selisih margin pendapatan BLU.</p>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-800 hover:bg-blue-900 text-white font-semibold text-sm rounded-xl shadow-sm transition">
                    Sahkan Penetapan Harga
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function hitungMargin() {
        const hpp = parseFloat(document.getElementById('hpp').value) || 0;
        const hargaJual = parseFloat(document.getElementById('harga_jual').value) || 0;
        const marginBox = document.getElementById('marginBox');
        const marginNilai = document.getElementById('marginNilai');

        if (hargaJual > 0 && hpp > 0) {
            const selisih = hargaJual - hpp;
            const persen = ((selisih / hpp) * 100).toFixed(1);

            if (selisih >= 0) {
                marginBox.className = "p-4 bg-green-50 border border-green-200 rounded-xl text-xs space-y-1";
                marginNilai.innerHTML = `Margin BLU Kampus: <strong>Rp ${selisih.toLocaleString('id-ID')}</strong> (${persen}%) per satuan unit/layanan.`;
            } else {
                marginBox.className = "p-4 bg-red-50 border border-red-200 rounded-xl text-xs space-y-1";
                marginNilai.innerHTML = `<span class="text-red-700 font-bold">Peringatan: Harga Jual lebih kecil dari HPP (Rugi)!</span>`;
            }
        }
    }
    document.addEventListener('DOMContentLoaded', hitungMargin);
</script>

@endsection

