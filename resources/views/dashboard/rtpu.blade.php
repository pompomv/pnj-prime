@extends('layouts.app')

@section('title', 'Dashboard RTPU — PNJ PRIME')

@section('content')

{{-- ─── Header ─────────────────────────────────────────────────────────────── --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard RTPU</h1>
    <p class="text-gray-500 text-sm mt-1">Verifikasi akun, publikasi inovasi, dan jadwal jasa.</p>
</div>

{{-- ─── Stat Cards ──────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-xs text-gray-500 mb-1">Akun Eksternal Menunggu</p>
        <p class="text-3xl font-bold text-yellow-600">{{ $stats['akun_menunggu'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-xs text-gray-500 mb-1">Inovasi Siap Publikasi</p>
        <p class="text-3xl font-bold text-blue-600">{{ $stats['siap_publikasi'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-xs text-gray-500 mb-1">Transaksi Lunas</p>
        <p class="text-3xl font-bold text-green-600">{{ $stats['transaksi_lunas'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-xs text-gray-500 mb-1">Jadwal Pending</p>
        <p class="text-3xl font-bold text-orange-600">{{ $stats['jadwal_pending'] }}</p>
    </div>
</div>

{{-- ─── Section 1: Akun Eksternal Menunggu Verifikasi ──────────────────────── --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800">Akun Eksternal Menunggu Verifikasi</h2>
    </div>
    @if($akunMenunggu->isEmpty())
        <p class="text-center text-gray-400 text-sm py-8">Tidak ada akun yang menunggu verifikasi.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">Nama / Instansi</th>
                        <th class="px-6 py-3 text-left">Tipe</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Identitas (NIK/NIB)</th>
                        <th class="px-6 py-3 text-left">Dokumen KYC</th>
                        <th class="px-6 py-3 text-left">Tanggal Daftar</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($akunMenunggu as $akun)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">
                            <div class="font-medium text-gray-900">{{ $akun->name }}</div>
                            @if($akun->nama_perusahaan)
                                <div class="text-xs text-gray-500 font-normal">{{ $akun->nama_perusahaan }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            @if($akun->jenis_akun === 'perusahaan')
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">Perusahaan</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-teal-100 text-teal-800" style="background-color: #E6F4F1; color: #0D6B5E;">Personal</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-gray-600 text-xs">{{ $akun->email }}</td>
                        <td class="px-6 py-3 text-gray-600 font-mono text-xs">
                            @if($akun->jenis_akun === 'perusahaan')
                                <div><span class="text-gray-400">NIB:</span> {{ $akun->nib ?? '—' }}</div>
                                <div><span class="text-gray-400">NPWP:</span> {{ $akun->npwp ?? '—' }}</div>
                            @else
                                <div><span class="text-gray-400">NIK:</span> {{ $akun->nik ?? '—' }}</div>
                                @if($akun->npwp)
                                    <div><span class="text-gray-400">NPWP:</span> {{ $akun->npwp }}</div>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-3 text-xs space-y-1">
                            @if($akun->foto_ktp || $akun->url_foto_ktp)
                                <a href="{{ asset('storage/' . ($akun->foto_ktp ?? $akun->url_foto_ktp)) }}" target="_blank"
                                    class="inline-flex items-center gap-1 text-blue-600 hover:underline">
                                    📄 KTP/Paspor
                                </a>
                            @endif
                            @if($akun->dok_npwp || $akun->foto_npwp || $akun->url_foto_npwp)
                                <div>
                                    <a href="{{ asset('storage/' . ($akun->dok_npwp ?? $akun->foto_npwp ?? $akun->url_foto_npwp)) }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-blue-600 hover:underline">
                                        📄 NPWP
                                    </a>
                                </div>
                            @endif
                            @if($akun->dok_nib_akta)
                                <div>
                                    <a href="{{ asset('storage/' . $akun->dok_nib_akta) }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-blue-600 hover:underline">
                                        📄 NIB / Akta
                                    </a>
                                </div>
                            @endif
                            @if(!$akun->foto_ktp && !$akun->url_foto_ktp && !$akun->dok_npwp && !$akun->dok_nib_akta)
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-gray-500 text-xs">{{ $akun->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center space-x-2">
                                <form action="{{ route('rtpu.verifikasi.setujui', $akun->id) }}" method="POST" onsubmit="return confirm('Setujui akun eksternal ini?')">
                                    @csrf
                                    <button type="submit" class="text-xs px-2.5 py-1 bg-green-100 text-green-700 font-semibold rounded-lg hover:bg-green-200 transition">
                                        ✓ Setujui
                                    </button>
                                </form>
                                <button type="button" onclick="tolakAkunModal('{{ $akun->id }}', '{{ $akun->name }}')" class="text-xs px-2.5 py-1 bg-red-100 text-red-700 font-semibold rounded-lg hover:bg-red-200 transition">
                                    ✕ Tolak
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- ─── Section 2: Inovasi Siap Dipublikasikan ─────────────────────────────── --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800">Inovasi Siap Dipublikasikan</h2>
        <p class="text-xs text-gray-500 mt-0.5">Lolos P3M, sudah ada harga, belum dipublikasi</p>
    </div>
    @if($siapPublikasi->isEmpty())
        <p class="text-center text-gray-400 text-sm py-8">Tidak ada inovasi siap publikasi.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">Judul</th>
                        <th class="px-6 py-3 text-left">Pengaju</th>
                        <th class="px-6 py-3 text-left">Tipe</th>
                        <th class="px-6 py-3 text-left">Harga Jual</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($siapPublikasi as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $item->judul }}</td>
                        <td class="px-6 py-3 text-gray-600">{{ $item->pengaju?->name ?? '—' }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $item->tipe_item === 'produk_fisik' ? 'bg-indigo-100 text-indigo-700' : 'bg-teal-100 text-teal-700' }}">
                                {{ $item->tipe_label }}
                            </span>
                        </td>
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $item->harga_format }}</td>
                        <td class="px-6 py-3">
                            <a href="{{ route('rtpu.publikasi.form', $item->id) }}" class="text-xs px-3 py-1.5 bg-blue-800 text-white font-semibold rounded-lg hover:bg-blue-900 transition shadow-sm">
                                Kelola Publikasi &rarr;
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- ─── Section 3: Antrian Penjadwalan Jasa ────────────────────────────────── --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800">Antrian Penjadwalan Jasa</h2>
    </div>
    @if($jadwalPending->isEmpty())
        <p class="text-center text-gray-400 text-sm py-8">Tidak ada jadwal jasa yang perlu ditentukan.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">Jasa</th>
                        <th class="px-6 py-3 text-left">Pembeli</th>
                        <th class="px-6 py-3 text-left">No. Order</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($jadwalPending as $jadwal)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-900">
                            {{ $jadwal->detailTransaksi?->inovasi?->judul ?? '—' }}
                        </td>
                        <td class="px-6 py-3 text-gray-600">
                            {{ $jadwal->detailTransaksi?->transaksi?->pembeli?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-3 font-mono text-xs text-gray-500">
                            {{ $jadwal->detailTransaksi?->transaksi?->nomor_order ?? '—' }}
                        </td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs bg-orange-100 text-orange-700">Menunggu Jadwal</span>
                        </td>
                        <td class="px-6 py-3">
                            <a href="{{ route('rtpu.jadwal.form', $jadwal->detail_transaksi_id) }}" class="text-xs px-3 py-1.5 bg-purple-700 text-white font-semibold rounded-lg hover:bg-purple-800 transition shadow-sm">
                                Jadwalkan &rarr;
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Modal Tolak Akun Eksternal --}}
<div id="modalTolak" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
        <h3 class="font-bold text-gray-900 text-lg mb-1">Tolak Akun Eksternal</h3>
        <p class="text-xs text-gray-500 mb-4" id="modalTolakNama"></p>
        <form id="formTolakAkun" method="POST" action="">
            @csrf
            <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
            <textarea name="catatan_verifikasi" required rows="3" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 mb-4" placeholder="Misal: Foto KTP tidak jelas / NPWP tidak terdaftar..."></textarea>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModalTolak()" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl">Tolak Akun</button>
            </div>
        </form>
    </div>
</div>

<script>
    function tolakAkunModal(id, name) {
        document.getElementById('modalTolakNama').textContent = 'Pengguna: ' + name;
        document.getElementById('formTolakAkun').action = '/rtpu/verifikasi/' + id + '/tolak';
        document.getElementById('modalTolak').classList.remove('hidden');
    }
    function closeModalTolak() {
        document.getElementById('modalTolak').classList.add('hidden');
    }
</script>

@endsection
