<?php

namespace App\Http\Controllers;

use App\Models\Inovasi;
use Illuminate\Http\Request;

/**
 * MarketplaceController
 * Menampilkan katalog etalase produk dan jasa riset PNJ PRIME (khusus tampilan katalog/detail tanpa checkout).
 */
class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Inovasi::where('is_published', true)
            ->where('status_kurasi', 'lolos_p3m')
            ->whereNotNull('harga_jual')
            ->with(['pengaju', 'media']);

        // Filter tipe
        if ($request->filled('tipe') && in_array($request->tipe, ['produk_fisik', 'jasa'])) {
            $query->where('tipe_item', $request->tipe);
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

        $items = $query->latest('dipublikasi_pada')->paginate(12)->withQueryString();

        return view('marketplace.index', compact('items'));
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

