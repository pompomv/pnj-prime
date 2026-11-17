<?php

namespace App\Http\Controllers;

use App\Models\Inovasi;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman utama (Home Page) PNJ PRIME
     */
    public function index()
    {
        // Ambil produk dan jasa yang sudah publish dan memiliki harga jual
        $sorotanInovasi = Inovasi::where('is_published', true)
            ->where('status_kurasi', 'lolos_p3m')
            ->whereNotNull('harga_jual')
            ->with(['pengaju', 'media'])
            ->latest('dipublikasi_pada')
            ->take(3)
            ->get();

        // Statistik
        $totalInovasi = Inovasi::where('is_published', true)->count();
        $totalProduk  = Inovasi::where('is_published', true)->where('tipe_item', 'produk_fisik')->count();
        $totalJasa    = Inovasi::where('is_published', true)->where('tipe_item', 'jasa')->count();
        $totalMitra   = User::where('role', 'eksternal')->where('status_verifikasi', 'disetujui')->count();

        return view('home', compact(
            'sorotanInovasi',
            'totalInovasi',
            'totalProduk',
            'totalJasa',
            'totalMitra'
        ));
    }
}
