@extends('layouts.app')

@section('title', 'Penjadwalan Jasa — PNJ PRIME')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-blue-800 hover:underline inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Dashboard RTPU
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Penjadwalan Layanan Jasa Riset</h1>
        <p class="text-gray-500 text-sm mt-0.5">
            Tentukan waktu pelaksanaan pengujian laboratorium dan alokasi ruangan/fasilitas.
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

    {{-- Detail Pesanan Jasa --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm mb-6">
        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-100 text-teal-700">
            Layanan Jasa Riset
        </span>
        <h2 class="text-lg font-bold text-gray-900 mt-1">{{ $detail->judul_saat_transaksi }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-100 text-xs text-gray-600">
            <div>
                <span class="text-gray-400 block">Pemohon / Pembeli</span>
                <span class="font-bold text-gray-800">{{ $detail->transaksi?->pembeli?->name ?? '—' }}</span>
                <span class="text-gray-500 block">{{ $detail->transaksi?->pembeli?->email ?? '' }}</span>
            </div>
            <div>
                <span class="text-gray-400 block">No. Transaksi</span>
                <span class="font-mono font-medium text-gray-800">{{ $detail->transaksi?->nomor_order ?? '—' }}</span>
            </div>
            <div>
                <span class="text-gray-400 block">Kuantitas</span>
                <span class="font-semibold text-gray-800">{{ $detail->kuantitas }} Sesi</span>
            </div>
        </div>
    </div>

    {{-- Form Penjadwalan --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('rtpu.jadwal.simpan', $detail->id) }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="waktu_mulai" class="block text-sm font-semibold text-gray-800 mb-1">
                        Waktu Mulai <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="datetime-local"
                        id="waktu_mulai"
                        name="waktu_mulai"
                        value="{{ old('waktu_mulai', $detail->jadwal?->waktu_mulai ? $detail->jadwal->waktu_mulai->format('Y-m-d\TH:i') : '') }}"
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                    >
                </div>

                <div>
                    <label for="waktu_selesai" class="block text-sm font-semibold text-gray-800 mb-1">
                        Waktu Selesai <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="datetime-local"
                        id="waktu_selesai"
                        name="waktu_selesai"
                        value="{{ old('waktu_selesai', $detail->jadwal?->waktu_selesai ? $detail->jadwal->waktu_selesai->format('Y-m-d\TH:i') : '') }}"
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                    >
                </div>
            </div>

            <div>
                <label for="lokasi" class="block text-sm font-semibold text-gray-800 mb-1">
                    Lokasi Ruangan / Laboratorium Pengujian <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="lokasi"
                    name="lokasi"
                    value="{{ old('lokasi', $detail->jadwal?->lokasi ?? '') }}"
                    placeholder="Contoh: Lab Uji Material Gedung H Lantai 2, Kampus PNJ"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                >
            </div>

            <div>
                <label for="keterangan_jadwal" class="block text-sm font-semibold text-gray-800 mb-1">
                    Instruksi / Catatan untuk Pemohon
                </label>
                <textarea
                    id="keterangan_jadwal"
                    name="keterangan_jadwal"
                    rows="3"
                    placeholder="Contoh: Harap membawa sampel material uji sebelum pukul 09.00 WIB dan mengenakan APD laboratorium..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                >{{ old('keterangan_jadwal', $detail->jadwal?->keterangan_jadwal ?? '') }}</textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-800 hover:bg-blue-900 text-white font-semibold text-sm rounded-xl shadow-sm transition">
                    Simpan &amp; Jadwalkan Layanan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

