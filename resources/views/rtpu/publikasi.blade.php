@extends('layouts.app')

@section('title', 'Kelola Publikasi Etalase — PNJ PRIME')

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-blue-800 hover:underline inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Dashboard RTPU
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Kelola Publikasi Etalase (PNJ PRIME)</h1>
        <p class="text-gray-500 text-sm mt-0.5">
            RTPU mengelola narasi promosi pemasaran dan keterbukaan produk/jasa ke etalase publik masyarakat &amp; industri.
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

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
        {{-- Ringkasan Item --}}
        <div class="pb-6 mb-6 border-b border-gray-100">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $inovasi->tipe_item === 'produk_fisik' ? 'bg-indigo-100 text-indigo-700' : 'bg-teal-100 text-teal-700' }}">
                    {{ $inovasi->tipe_label }}
                </span>
                <span class="text-xs text-gray-400">Pengaju: {{ $inovasi->pengaju?->name }}</span>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mt-2">{{ $inovasi->judul }}</h2>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4 p-4 bg-gray-50 rounded-xl text-xs">
                <div>
                    <span class="text-gray-400 block">Status Kurasi</span>
                    <span class="font-bold text-green-700">✓ Lolos P3M</span>
                </div>
                <div>
                    <span class="text-gray-400 block">HPP</span>
                    <span class="font-semibold text-gray-700">{{ $inovasi->hpp_format }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Harga Jual Resmi</span>
                    <span class="font-bold text-blue-800 text-sm">{{ $inovasi->harga_format }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Ketersediaan</span>
                    <span class="font-semibold text-gray-700">
                        @if($inovasi->tipe_item === 'produk_fisik')
                            {{ $inovasi->stok ?? 0 }} {{ $inovasi->satuan }}
                        @else
                            Tersedia (Booking Jasa)
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <form action="{{ route('rtpu.publikasi.simpan', $inovasi->id) }}" method="POST" class="space-y-6">
            @csrf

            {{-- Status Publikasi --}}
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">
                    Status Etalase Publik <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition border-gray-200 has-[:checked]:border-green-600 has-[:checked]:bg-green-50/50">
                        <input type="radio" name="is_published" value="1" class="h-4 w-4 text-green-600" {{ old('is_published', $inovasi->is_published ? '1' : '0') == '1' ? 'checked' : '' }}>
                        <div class="ml-3">
                            <span class="block text-sm font-bold text-green-800">Publikasikan ke Etalase</span>
                            <span class="block text-xs text-gray-500">Item akan langsung tampil dan dapat dicari di katalog pasar PNJ PRIME.</span>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition border-gray-200 has-[:checked]:border-gray-600 has-[:checked]:bg-gray-100/50">
                        <input type="radio" name="is_published" value="0" class="h-4 w-4 text-gray-600" {{ old('is_published', $inovasi->is_published ? '1' : '0') == '0' ? 'checked' : '' }}>
                        <div class="ml-3">
                            <span class="block text-sm font-bold text-gray-700">Simpan sebagai Draft</span>
                            <span class="block text-xs text-gray-500">Sembunyikan dari katalog umum (hanya dapat diakses tim internal).</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Deskripsi Promosi --}}
            <div>
                <label for="deskripsi_promosi" class="block text-sm font-semibold text-gray-800 mb-1">
                    Narasi Promosi &amp; Keunggulan Produk (Marketing Copy)
                </label>
                <textarea
                    id="deskripsi_promosi"
                    name="deskripsi_promosi"
                    rows="4"
                    placeholder="Tuliskan daya tarik utama, manfaat bagi industri/masyarakat, dan garansi/sertifikasi..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                >{{ old('deskripsi_promosi', $inovasi->deskripsi_promosi) }}</textarea>
                <span class="text-xs text-gray-400 mt-1 block">Narasi ini akan disorot di bagian atas halaman detail katalog untuk menarik calon pembeli atau mitra industri.</span>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-800 hover:bg-blue-900 text-white font-semibold text-sm rounded-xl shadow-sm transition">
                    Simpan Pengaturan Publikasi
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

