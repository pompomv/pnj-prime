@extends('layouts.app')

@section('title', 'Dashboard — PNJ PRIME')

@section('content')

{{-- ─── Header ─────────────────────────────────────────────────────────────── --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">
        Selamat datang, <span class="text-blue-800">{{ $user->name }}</span> 👋
    </h1>
    <p class="text-gray-500 text-sm mt-1">
        Kelola inovasi yang Anda ajukan ke PNJ PRIME.
    </p>
</div>

{{-- ─── Stat Cards ──────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Inovasi Diajukan</p>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Inovasi Lolos Kurasi</p>
        <p class="text-3xl font-bold text-green-600">{{ $stats['lolos'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Inovasi Dipublikasi</p>
        <p class="text-3xl font-bold text-blue-600">{{ $stats['published'] }}</p>
    </div>
</div>

{{-- ─── Table ───────────────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800">Inovasi Saya</h2>
        <a href="{{ route('inovasi.create') }}" class="bg-blue-800 hover:bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            + Ajukan Inovasi Baru
        </a>
    </div>

    @if($inovasiList->isEmpty())
        <div class="py-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p>Belum ada inovasi yang diajukan.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">Judul</th>
                        <th class="px-6 py-3 text-left">Tipe</th>
                        <th class="px-6 py-3 text-left">Status Kurasi</th>
                        <th class="px-6 py-3 text-left">Harga Jual</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($inovasiList as $inovasi)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $inovasi->judul }}</td>
                        <td class="px-6 py-4">
                            @if($inovasi->tipe_item === 'produk_fisik')
                                <span class="px-2 py-0.5 rounded-full text-xs bg-indigo-100 text-indigo-700">Produk Fisik</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs bg-teal-100 text-teal-700">Jasa</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColor = match($inovasi->status_kurasi) {
                                    'menunggu_validasi' => 'bg-yellow-100 text-yellow-700',
                                    'revisi'            => 'bg-orange-100 text-orange-700',
                                    'ditolak'           => 'bg-red-100 text-red-700',
                                    'lolos_p3m'         => 'bg-green-100 text-green-700',
                                    default             => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $statusColor }}">
                                {{ $inovasi->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $inovasi->harga_jual ? $inovasi->harga_format : '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('inovasi.show', $inovasi->id) }}" class="text-blue-600 hover:underline text-xs font-semibold">Detail &rarr;</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
