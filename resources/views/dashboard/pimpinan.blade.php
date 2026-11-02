@extends('layouts.app')

@section('title', 'Dashboard Pimpinan — PNJ PRIME')

@section('content')

{{-- ─── Header ─────────────────────────────────────────────────────────────── --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard Pimpinan</h1>
    <p class="text-gray-500 text-sm mt-1">Pantau inovasi dan tetapkan harga jual.</p>
</div>

{{-- ─── Stat Cards ──────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Total Inovasi Lolos P3M</p>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['lolos_p3m'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Sudah Ada Harga</p>
        <p class="text-3xl font-bold text-green-600">{{ $stats['sudah_ada_harga'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Total Transaksi Lunas</p>
        <p class="text-3xl font-bold text-blue-600">{{ $stats['transaksi_lunas'] }}</p>
    </div>
</div>

{{-- ─── Tabel: Belum Ada Harga ─────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800">Inovasi Lolos P3M — Belum Ditetapkan Harga</h2>
        <p class="text-xs text-gray-500 mt-0.5">Inovasi yang sudah lolos kurasi namun belum ada harga jualnya</p>
    </div>

    @if($belumHarga->isEmpty())
        <p class="text-center text-gray-400 text-sm py-8">Semua inovasi sudah memiliki harga. 🎉</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">Judul Inovasi</th>
                        <th class="px-6 py-3 text-left">Pengaju</th>
                        <th class="px-6 py-3 text-left">Tipe</th>
                        <th class="px-6 py-3 text-left">Status Harga</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($belumHarga as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $item->judul }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->pengaju?->name ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $item->tipe_item === 'produk_fisik' ? 'bg-indigo-100 text-indigo-700' : 'bg-teal-100 text-teal-700' }}">
                                {{ $item->tipe_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700">Belum Ada Harga</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('harga.form', $item->id) }}" class="text-xs font-semibold px-3 py-1.5 bg-blue-800 text-white rounded-lg hover:bg-blue-900 transition-colors shadow-sm">
                                Tetapkan Harga &rarr;
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- ─── Tabel: Sudah Ada Harga ─────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800">Semua Inovasi dengan Harga Jual</h2>
    </div>

    @if($sudahHarga->isEmpty())
        <p class="text-center text-gray-400 text-sm py-8">Belum ada inovasi yang memiliki harga jual.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">Judul Inovasi</th>
                        <th class="px-6 py-3 text-left">Pengaju</th>
                        <th class="px-6 py-3 text-left">Tipe</th>
                        <th class="px-6 py-3 text-left">HPP</th>
                        <th class="px-6 py-3 text-left">Harga Jual</th>
                        <th class="px-6 py-3 text-left">Dipublikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($sudahHarga as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $item->judul }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->pengaju?->name ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $item->tipe_item === 'produk_fisik' ? 'bg-indigo-100 text-indigo-700' : 'bg-teal-100 text-teal-700' }}">
                                {{ $item->tipe_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->hpp_format }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $item->harga_format }}</td>
                        <td class="px-6 py-4">
                            @if($item->is_published)
                                <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700">Ya</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-500">Belum</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
