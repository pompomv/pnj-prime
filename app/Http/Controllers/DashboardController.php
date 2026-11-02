<?php

namespace App\Http\Controllers;

use App\Models\Inovasi;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'dosen', 'mahasiswa' => $this->dashboardPengaju($user),
            'p3m'                => $this->dashboardP3m($user),
            'rtpu'               => $this->dashboardRtpu($user),
            'pimpinan'           => $this->dashboardPimpinan($user),
            'eksternal'          => $this->dashboardEksternal($user),
            default              => redirect()->route('login'),
        };
    }

    private function dashboardPengaju(User $user)
    {
        $inovasiQuery = Inovasi::where('pengaju_id', $user->id);

        $stats = [
            'total'      => (clone $inovasiQuery)->count(),
            'lolos'      => (clone $inovasiQuery)->where('status_kurasi', 'lolos_p3m')->count(),
            'published'  => (clone $inovasiQuery)->where('is_published', true)->count(),
        ];

        $inovasiList = (clone $inovasiQuery)->latest()->get();

        return view('dashboard.pengaju', compact('user', 'stats', 'inovasiList'));
    }

    private function dashboardP3m(User $user)
    {
        $stats = [
            'menunggu' => Inovasi::where('status_kurasi', 'menunggu_validasi')->count(),
            'revisi'   => Inovasi::where('status_kurasi', 'revisi')->count(),
            'lolos'    => Inovasi::where('status_kurasi', 'lolos_p3m')->count(),
        ];

        $antrian = Inovasi::whereIn('status_kurasi', ['menunggu_validasi', 'revisi'])
            ->with('pengaju')
            ->latest()
            ->get();

        return view('dashboard.p3m', compact('user', 'stats', 'antrian'));
    }

    private function dashboardRtpu(User $user)
    {
        $stats = [
            'akun_menunggu'    => User::where('role', 'eksternal')
                ->where('status_verifikasi', 'menunggu')
                ->count(),
            'siap_publikasi'   => Inovasi::where('status_kurasi', 'lolos_p3m')
                ->whereNotNull('harga_jual')
                ->where('is_published', false)
                ->count(),
            'transaksi_lunas'  => Transaksi::where('status_pembayaran', 'lunas')->count(),
            'jadwal_pending'   => \App\Models\PenjadwalanJasa::where('status_jadwal', 'menunggu_jadwal')->count(),
        ];

        $akunMenunggu = User::where('role', 'eksternal')
            ->where('status_verifikasi', 'menunggu')
            ->latest()
            ->get();

        $siapPublikasi = Inovasi::where('status_kurasi', 'lolos_p3m')
            ->whereNotNull('harga_jual')
            ->where('is_published', false)
            ->with('pengaju')
            ->latest()
            ->get();

        $jadwalPending = \App\Models\PenjadwalanJasa::where('status_jadwal', 'menunggu_jadwal')
            ->with(['detailTransaksi.transaksi.pembeli', 'detailTransaksi.inovasi'])
            ->latest()
            ->get();

        return view('dashboard.rtpu', compact('user', 'stats', 'akunMenunggu', 'siapPublikasi', 'jadwalPending'));
    }

    private function dashboardPimpinan(User $user)
    {
        $stats = [
            'lolos_p3m'        => Inovasi::where('status_kurasi', 'lolos_p3m')->count(),
            'sudah_ada_harga'  => Inovasi::where('status_kurasi', 'lolos_p3m')->whereNotNull('harga_jual')->count(),
            'transaksi_lunas'  => Transaksi::where('status_pembayaran', 'lunas')->count(),
        ];

        $belumHarga = Inovasi::where('status_kurasi', 'lolos_p3m')
            ->whereNull('harga_jual')
            ->with('pengaju')
            ->latest()
            ->get();

        $sudahHarga = Inovasi::where('status_kurasi', 'lolos_p3m')
            ->whereNotNull('harga_jual')
            ->with('pengaju')
            ->latest()
            ->get();

        return view('dashboard.pimpinan', compact('user', 'stats', 'belumHarga', 'sudahHarga'));
    }

    private function dashboardEksternal(User $user)
    {
        $stats = [];
        $transaksiList = collect();

        if ($user->status_verifikasi === 'disetujui') {
            $stats = [
                'total_pesanan'  => Transaksi::where('pembeli_id', $user->id)->count(),
                'pesanan_aktif'  => Transaksi::where('pembeli_id', $user->id)
                    ->whereIn('status_pembayaran', ['menunggu_pembayaran', 'lunas'])
                    ->count(),
            ];

            $transaksiList = Transaksi::where('pembeli_id', $user->id)
                ->latest()
                ->get();
        }

        return view('dashboard.eksternal', compact('user', 'stats', 'transaksiList'));
    }
}
