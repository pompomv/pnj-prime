<?php

namespace App\Http\Controllers;

use App\Models\Inovasi;
use Illuminate\Http\Request;

/**
 * MarketplaceController
 * Memisahkan dan mengelola etalase Produk Inovasi & Layanan Lab PNJ PRIME secara independen.
 */
class MarketplaceController extends Controller
{
    /**
     * Katalog Terpadu dengan Switcher Tab / Mode
     */
    public function index(Request $request)
    {
        $mode = $request->get('mode', 'semua');
        if ($request->has('tipe')) {
            $mode = $request->tipe === 'jasa' ? 'lab' : 'produk';
        }

        return $this->renderCatalog($request, $mode);
    }

    /**
     * Halaman Khusus: Katalog Produk Inovasi Terapan
     */
    public function produk(Request $request)
    {
        return $this->renderCatalog($request, 'produk');
    }

    /**
     * Halaman Khusus: Layanan Pengujian Lab & Kalibrasi
     */
    public function layananLab(Request $request)
    {
        return $this->renderCatalog($request, 'lab');
    }

    /**
     * Render data katalog berdasarkan mode terpisah
     */
    private function renderCatalog(Request $request, string $mode)
    {
        $query = Inovasi::where('is_published', true)
            ->where('status_kurasi', 'lolos_p3m')
            ->whereNotNull('harga_jual')
            ->with(['pengaju', 'media']);

        // Pemisahan berdasarkan mode
        if ($mode === 'produk') {
            $query->where('tipe_item', 'produk_fisik');
        } elseif ($mode === 'lab') {
            $query->where('tipe_item', 'jasa');
        }

        // Sub-filter Kategori
        $kategori = $request->get('kategori', 'semua');
        if ($kategori !== 'semua') {
            if ($kategori === 'rekayasa' || $kategori === 'mesin') {
                $query->where(function ($q) {
                    $q->where('judul', 'ILIKE', '%mesin%')
                      ->orWhere('judul', 'ILIKE', '%cnc%')
                      ->orWhere('deskripsi', 'ILIKE', '%manufaktur%');
                });
            } elseif ($kategori === 'otomasi' || $kategori === 'iot') {
                $query->where(function ($q) {
                    $q->where('judul', 'ILIKE', '%iot%')
                      ->orWhere('judul', 'ILIKE', '%controller%')
                      ->orWhere('judul', 'ILIKE', '%elektro%');
                });
            } elseif ($kategori === 'edukasi' || $kategori === 'trainer') {
                $query->where(function ($q) {
                    $q->where('judul', 'ILIKE', '%trainer%')
                      ->orWhere('judul', 'ILIKE', '%plc%')
                      ->orWhere('judul', 'ILIKE', '%modul%');
                });
            } elseif ($kategori === 'material') {
                $query->where(function ($q) {
                    $q->where('judul', 'ILIKE', '%material%')
                      ->orWhere('judul', 'ILIKE', '%tarik%')
                      ->orWhere('judul', 'ILIKE', '%baja%');
                });
            } elseif ($kategori === 'kalibrasi') {
                $query->where(function ($q) {
                    $q->where('judul', 'ILIKE', '%kalibrasi%')
                      ->orWhere('deskripsi', 'ILIKE', '%kalibrasi%');
                });
            } elseif ($kategori === 'akustik') {
                $query->where(function ($q) {
                    $q->where('judul', 'ILIKE', '%akustik%')
                      ->orWhere('judul', 'ILIKE', '%kebisingan%');
                });
            }
        }

        // Pencarian teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'ILIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'ILIKE', "%{$search}%")
                  ->orWhere('deskripsi_promosi', 'ILIKE', "%{$search}%");
            });
        }

        // Pengurutan
        $sort = $request->get('sort', 'terpopuler');
        switch ($sort) {
            case 'terbaru':
                $query->latest('dipublikasi_pada');
                break;
            case 'harga_asc':
                $query->orderBy('harga_jual', 'asc');
                break;
            case 'harga_desc':
                $query->orderBy('harga_jual', 'desc');
                break;
            case 'terpopuler':
            default:
                $query->latest('dipublikasi_pada');
                break;
        }

        $items = $query->paginate(6)->withQueryString();

        // Hitung total untuk tabs & counter
        $totalProduk = Inovasi::where('is_published', true)->where('tipe_item', 'produk_fisik')->count();
        $totalLab    = Inovasi::where('is_published', true)->where('tipe_item', 'jasa')->count();
        $totalSemua  = $totalProduk + $totalLab;

        return view('marketplace.index', compact(
            'items',
            'mode',
            'kategori',
            'sort',
            'totalSemua',
            'totalProduk',
            'totalLab'
        ));
    }

    public function show(string $id)
    {
        $inovasi = Inovasi::where('is_published', true)
            ->where('status_kurasi', 'lolos_p3m')
            ->with(['pengaju', 'media', 'publisher'])
            ->findOrFail($id);

        return view('marketplace.show', compact('inovasi'));
    }
}
