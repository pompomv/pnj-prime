@extends('layouts.app')

@section('title', 'Ajukan Inovasi Baru — PNJ PRIME')

@section('content')

<div class="max-w-4xl mx-auto">
    {{-- Breadcrumb / Back --}}
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-blue-800 hover:underline inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Dashboard
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Formulir Pengajuan Inovasi Riset</h1>
        <p class="text-gray-500 text-sm mt-1">
            Ajukan hasil riset atau prototipe Anda untuk proses kurasi oleh P3M Politeknik Negeri Jakarta.
        </p>
    </div>

    {{-- Error validation alerts --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
            <p class="font-semibold mb-1">Terdapat kesalahan pengisian formulir:</p>
            <ul class="list-disc list-inside space-y-0.5 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('inovasi.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Judul Inovasi --}}
            <div>
                <label for="judul" class="block text-sm font-semibold text-gray-800 mb-1">
                    Judul Inovasi / Riset <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="judul"
                    name="judul"
                    value="{{ old('judul') }}"
                    placeholder="Contoh: Mesin Pemilah Sampah Organik Berbasis AI"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                >
            </div>

            {{-- Tipe Item --}}
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">
                    Tipe Inovasi <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition border-gray-200 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50">
                        <input type="radio" name="tipe_item" value="produk_fisik" class="h-4 w-4 text-blue-600" {{ old('tipe_item', 'produk_fisik') === 'produk_fisik' ? 'checked' : '' }} onchange="toggleItemFields()">
                        <div class="ml-3">
                            <span class="block text-sm font-medium text-gray-900">Produk Fisik</span>
                            <span class="block text-xs text-gray-500">Alat, perangkat keras, prototipe mesin, atau produk jadi yang memiliki kuantitas/stok.</span>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition border-gray-200 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50">
                        <input type="radio" name="tipe_item" value="jasa" class="h-4 w-4 text-blue-600" {{ old('tipe_item') === 'jasa' ? 'checked' : '' }} onchange="toggleItemFields()">
                        <div class="ml-3">
                            <span class="block text-sm font-medium text-gray-900">Jasa / Layanan Pengujian</span>
                            <span class="block text-xs text-gray-500">Layanan pengujian lab, konsultasi teknis, kalibrasi, atau penggunaan fasilitas ruangan.</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Deskripsi Inovasi --}}
            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-gray-800 mb-1">
                    Deskripsi Lengkap &amp; Spesifikasi <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="5"
                    placeholder="Jelaskan latar belakang riset, spesifikasi teknis inovasi, keunggulan produk/layanan, dan target pengguna..."
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                >{{ old('deskripsi') }}</textarea>
            </div>

            {{-- Stok & Satuan (Hanya Produk Fisik) --}}
            <div id="stokFieldGroup" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="stok" class="block text-sm font-semibold text-gray-800 mb-1">
                        Jumlah Stok Tersedia
                    </label>
                    <input
                        type="number"
                        id="stok"
                        name="stok"
                        value="{{ old('stok', 1) }}"
                        min="0"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                    >
                </div>
                <div>
                    <label for="satuan" class="block text-sm font-semibold text-gray-800 mb-1">
                        Satuan Item
                    </label>
                    <input
                        type="text"
                        id="satuan"
                        name="satuan"
                        value="{{ old('satuan', 'unit') }}"
                        placeholder="Contoh: unit, pcs, paket, sesi"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                    >
                </div>
            </div>

            {{-- Dokumen Pendukung & Foto --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="url_dokumen_pendukung" class="block text-sm font-semibold text-gray-800 mb-1">
                        URL Dokumen Pendukung (PDF/Drive)
                    </label>
                    <input
                        type="url"
                        id="url_dokumen_pendukung"
                        name="url_dokumen_pendukung"
                        value="{{ old('url_dokumen_pendukung') }}"
                        placeholder="https://drive.google.com/file/..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                    >
                    <span class="text-xs text-gray-400 mt-1 block">Tautan ke proposal, datasheet, atau laporan hasil uji.</span>
                </div>
                <div>
                    <label for="url_foto_prototipe" class="block text-sm font-semibold text-gray-800 mb-1">
                        URL Foto Prototipe / Brosur
                    </label>
                    <input
                        type="url"
                        id="url_foto_prototipe"
                        name="url_foto_prototipe"
                        value="{{ old('url_foto_prototipe') }}"
                        placeholder="https://images.unsplash.com/... atau URL gambar"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                    >
                    <span class="text-xs text-gray-400 mt-1 block">Tautan gambar visual produk/ruangan untuk ditampilkan di etalase.</span>
                </div>
            </div>

            {{-- Informasi Alur --}}
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-900 space-y-1">
                <p class="font-semibold text-sm">ℹ️ Catatan Alur Hilirisasi:</p>
                <p>1. Setelah dikirim, status inovasi menjadi <strong>Menunggu Validasi P3M</strong>.</p>
                <p>2. Jika dinyatakan lolos, Pimpinan PNJ akan menetapkan HPP &amp; Harga Jual resmi.</p>
                <p>3. Selanjutnya RTPU akan melengkapi promosi dan mempublikasikan ke etalase PNJ PRIME.</p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-800 hover:bg-blue-900 text-white font-semibold text-sm rounded-xl shadow-sm transition">
                    Kirim Pengajuan Inovasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleItemFields() {
        const isJasa = document.querySelector('input[name="tipe_item"]:checked').value === 'jasa';
        const stokGroup = document.getElementById('stokFieldGroup');
        const satuanInput = document.getElementById('satuan');

        if (isJasa) {
            document.getElementById('stok').value = '';
            stokGroup.style.display = 'none';
            satuanInput.value = 'sesi';
        } else {
            stokGroup.style.display = 'grid';
            if (satuanInput.value === 'sesi') {
                satuanInput.value = 'unit';
            }
        }
    }
    document.addEventListener('DOMContentLoaded', toggleItemFields);
</script>

@endsection

