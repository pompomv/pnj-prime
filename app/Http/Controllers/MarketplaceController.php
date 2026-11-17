<?php

namespace App\Http\Controllers;

use App\Models\Inovasi;
use Illuminate\Http\Request;

/**
 * MarketplaceController
 * Menampilkan katalog etalase produk dan jasa riset PNJ PRIME (publik & interaktif sesuai Figma mockup).
 */
class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Inovasi::where('is_published', true)
            ->where('status_kurasi', 'lolos_p3m')
            ->whereNotNull('harga_jual')
            ->with(['pengaju', 'media']);

        // Filter kategori / tipe
        $kategori = $request->get('kategori', $request->get('tipe', 'semua'));

        if ($kategori === 'produk_fisik' || $kategori === 'rekayasa') {
            $query->where('tipe_item', 'produk_fisik');
        } elseif ($kategori === 'jasa' || $kategori === 'pengujian') {
            $query->where('tipe_item', 'jasa');
        } elseif ($kategori === 'kalibrasi') {
            $query->where('tipe_item', 'jasa')
                  ->where(function ($q) {
                      $q->where('judul', 'ILIKE', '%kalibrasi%')
                        ->orWhere('deskripsi', 'ILIKE', '%kalibrasi%');
                  });
        } elseif ($kategori === 'pelatihan') {
            $query->where(function ($q) {
                $q->where('judul', 'ILIKE', '%pelatihan%')
                  ->orWhere('judul', 'ILIKE', '%trainer%')
                  ->orWhere('deskripsi', 'ILIKE', '%pelatihan%');
            });
        }

        // Pencarian judul / deskripsi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'ILIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'ILIKE', "%{$search}%")
                  ->orWhere('deskripsi_promosi', 'ILIKE', "%{$search}%");
            });
        }

        // Pengurutan (Sorting)
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

        // Hitung total untuk tabs kategori
        $totalSemua = Inovasi::where('is_published', true)->count();
        $totalRekayasa = Inovasi::where('is_published', true)->where('tipe_item', 'produk_fisik')->count();
        $totalPengujian = Inovasi::where('is_published', true)->where('tipe_item', 'jasa')->count();
        $totalKalibrasi = Inovasi::where('is_published', true)
            ->where(function ($q) {
                $q->where('judul', 'ILIKE', '%kalibrasi%')
                  ->orWhere('deskripsi', 'ILIKE', '%kalibrasi%');
            })->count();
        $totalPelatihan = Inovasi::where('is_published', true)
            ->where(function ($q) {
                $q->where('judul', 'ILIKE', '%pelatihan%')
                  ->orWhere('judul', 'ILIKE', '%trainer%')
                  ->orWhere('deskripsi', 'ILIKE', '%pelatihan%');
            })->count();

        return view('marketplace.index', compact(
            'items',
            'kategori',
            'sort',
            'totalSemua',
            'totalRekayasa',
            'totalPengujian',
            'totalKalibrasi',
            'totalPelatihan'
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
