@extends('layouts.app')

@section('title', 'Dashboard Pembeli — PNJ PRIME')

@section('content')

{{-- ─── Header ─────────────────────────────────────────────────────────────── --}}
<div class="mb-8 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Selamat datang, <span class="text-blue-800">{{ $user->name }}</span> 👋
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Kelola pesanan dan jelajahi layanan riset unggulan di PNJ PRIME.
        </p>
    </div>
    <div>
        <a href="{{ route('marketplace.index') }}" class="inline-flex items-center px-5 py-2.5 bg-blue-800 hover:bg-blue-900 text-white font-semibold text-sm rounded-xl shadow-sm transition">
            <span class="mr-2">🛍️</span> Jelajahi Etalase Riset &amp; Jasa &rarr;
        </a>
    </div>
</div>

{{-- ─── Status Verifikasi Alert ─────────────────────────────────────────────── --}}
@if($user->status_verifikasi === 'menunggu')
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8 rounded-r-xl">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-semibold text-yellow-800">Akun Anda sedang dalam proses verifikasi oleh Tim RTPU.</p>
                <p class="text-xs text-yellow-700 mt-1">Harap menunggu persetujuan verifikasi data NIK & NPWP sebelum dapat melakukan transaksi pembelian produk/jasa.</p>
            </div>
        </div>
    </div>
@elseif($user->status_verifikasi === 'ditolak')
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded-r-xl">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-semibold text-red-800">Verifikasi akun Anda ditolak.</p>
                @if($user->catatan_verifikasi)
                    <p class="text-xs text-red-700 mt-1"><strong>Alasan:</strong> {{ $user->catatan_verifikasi }}</p>
                @endif
                <p class="text-xs text-red-700 mt-1">Silakan hubungi RTPU atau perbarui dokumen identitas Anda.</p>
            </div>
        </div>
    </div>
@endif

{{-- ─── Stat Cards (Jika Disetujui) ────────────────────────────────────────── --}}
@if($user->status_verifikasi === 'disetujui')
<div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Total Pesanan</p>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_pesanan'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Pesanan Aktif</p>
        <p class="text-3xl font-bold text-blue-600">{{ $stats['pesanan_aktif'] ?? 0 }}</p>
    </div>
</div>

{{-- ─── Table Pesanan ───────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800">Riwayat Transaksi</h2>
        <span class="text-xs text-gray-500">Rekening Resmi BLU PNJ</span>
    </div>

    @if($transaksiList->isEmpty())
        <div class="py-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <p>Belum ada transaksi pembelian produk atau booking jasa.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">Nomor Order</th>
                        <th class="px-6 py-3 text-left">Total Pembayaran</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transaksiList as $transaksi)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-mono font-medium text-gray-900">{{ $transaksi->nomor_order }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $transaksi->total_format }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusColor = match($transaksi->status_pembayaran) {
                                    'lunas'               => 'bg-green-100 text-green-700',
                                    'menunggu_pembayaran' => 'bg-yellow-100 text-yellow-700',
                                    'gagal'               => 'bg-red-100 text-red-700',
                                    'dibatalkan'          => 'bg-gray-100 text-gray-700',
                                    default               => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $statusColor }}">
                                {{ ucfirst(str_replace('_', ' ', $transaksi->status_pembayaran)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $transaksi->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4">
                            <a href="#" class="text-blue-600 hover:underline text-xs font-medium">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endif

@endsection

