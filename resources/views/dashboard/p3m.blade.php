@extends('layouts.app')

@section('title', 'Dashboard P3M — PNJ PRIME')

@section('content')

{{-- ─── Header ─────────────────────────────────────────────────────────────── --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard P3M</h1>
    <p class="text-gray-500 text-sm mt-1">Kelola antrian kurasi inovasi masuk.</p>
</div>

{{-- ─── Stat Cards ──────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Menunggu Kurasi</p>
        <p class="text-3xl font-bold text-yellow-600">{{ $stats['menunggu'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Perlu Revisi</p>
        <p class="text-3xl font-bold text-orange-600">{{ $stats['revisi'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Lolos Kurasi</p>
        <p class="text-3xl font-bold text-green-600">{{ $stats['lolos'] }}</p>
    </div>
</div>

{{-- ─── Antrian Kurasi ──────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800">Antrian Kurasi Inovasi</h2>
        <p class="text-xs text-gray-500 mt-0.5">Inovasi dengan status Menunggu Validasi dan Perlu Revisi</p>
    </div>

    @if($antrian->isEmpty())
        <div class="py-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p>Tidak ada antrian kurasi saat ini.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">Judul Inovasi</th>
                        <th class="px-6 py-3 text-left">Pengaju</th>
                        <th class="px-6 py-3 text-left">Tipe</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Tanggal Ajuan</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($antrian as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $item->judul }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->pengaju?->name ?? '—' }}</td>
                        <td class="px-6 py-4">
                            @if($item->tipe_item === 'produk_fisik')
                                <span class="px-2 py-0.5 rounded-full text-xs bg-indigo-100 text-indigo-700">Produk Fisik</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs bg-teal-100 text-teal-700">Jasa</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($item->status_kurasi === 'menunggu_validasi')
                                <span class="px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700">Menunggu Validasi</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs bg-orange-100 text-orange-700">Perlu Revisi</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $item->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('kurasi.show', $item->id) }}" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 bg-blue-800 hover:bg-blue-900 text-white rounded-lg transition-colors shadow-sm">
                                Review &amp; Kurasi &rarr;
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
