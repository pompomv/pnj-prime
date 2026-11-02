@extends('layouts.app')

@section('title', 'Edit Inovasi — PNJ PRIME')

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('inovasi.show', $inovasi->id) }}" class="text-sm font-medium text-blue-800 hover:underline inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Detail Inovasi
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Edit Pengajuan Inovasi</h1>
        <p class="text-gray-500 text-sm mt-1">Perbarui data atau lakukan revisi sesuai masukan tim P3M.</p>
    </div>

    @if($inovasi->status_kurasi === 'revisi' && $inovasi->catatan_revisi)
        <div class="mb-6 p-4 bg-orange-50 border-l-4 border-orange-500 rounded-r-xl text-orange-900">
            <p class="text-sm font-bold flex items-center">
                <svg class="w-5 h-5 mr-1.5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                Catatan Revisi dari P3M:
            </p>
            <p class="text-sm mt-1 pl-6">{{ $inovasi->catatan_revisi }}</p>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('inovasi.update', $inovasi->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="judul" class="block text-sm font-semibold text-gray-800 mb-1">
                    Judul Inovasi <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="judul"
                    name="judul"
                    value="{{ old('judul', $inovasi->judul) }}"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                >
            </div>

            <div>
                <span class="block text-sm font-semibold text-gray-800 mb-1">Tipe Inovasi</span>
                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $inovasi->tipe_item === 'produk_fisik' ? 'bg-indigo-100 text-indigo-700' : 'bg-teal-100 text-teal-700' }}">
                    {{ $inovasi->tipe_label }} (Tipe tidak dapat diubah)
                </div>
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-gray-800 mb-1">
                    Deskripsi &amp; Spesifikasi Teknis <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="6"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                >{{ old('deskripsi', $inovasi->deskripsi) }}</textarea>
            </div>

            @if($inovasi->tipe_item === 'produk_fisik')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="stok" class="block text-sm font-semibold text-gray-800 mb-1">Stok</label>
                    <input
                        type="number"
                        id="stok"
                        name="stok"
                        value="{{ old('stok', $inovasi->stok) }}"
                        min="0"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                    >
                </div>
                <div>
                    <label for="satuan" class="block text-sm font-semibold text-gray-800 mb-1">Satuan</label>
                    <input
                        type="text"
                        id="satuan"
                        name="satuan"
                        value="{{ old('satuan', $inovasi->satuan) }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                    >
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="url_dokumen_pendukung" class="block text-sm font-semibold text-gray-800 mb-1">
                        URL Dokumen Pendukung
                    </label>
                    <input
                        type="url"
                        id="url_dokumen_pendukung"
                        name="url_dokumen_pendukung"
                        value="{{ old('url_dokumen_pendukung', $inovasi->url_dokumen_pendukung) }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                    >
                </div>
                <div>
                    <label for="url_foto_prototipe" class="block text-sm font-semibold text-gray-800 mb-1">
                        URL Foto Prototipe
                    </label>
                    <input
                        type="url"
                        id="url_foto_prototipe"
                        name="url_foto_prototipe"
                        value="{{ old('url_foto_prototipe', $inovasi->url_foto_prototipe) }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                    >
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('inovasi.show', $inovasi->id) }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-800 hover:bg-blue-900 text-white font-semibold text-sm rounded-xl shadow-sm transition">
                    Simpan &amp; Kirim Ulang Validasi
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

