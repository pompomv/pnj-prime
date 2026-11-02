<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Inovasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * HargaController
 * Digunakan oleh Pimpinan untuk menetapkan HPP dan Harga Jual (Harga Pasar).
 */
class HargaController extends Controller
{
    public function form(string $id)
    {
        $inovasi = Inovasi::with(['pengaju', 'kurator'])->findOrFail($id);

        if ($inovasi->status_kurasi !== 'lolos_p3m') {
            return redirect()->route('dashboard')
                ->with('error', 'Inovasi ini belum lolos kurasi P3M.');
        }

        return view('harga.form', compact('inovasi'));
    }

    public function tetapkan(Request $request, string $id)
    {
        $inovasi = Inovasi::findOrFail($id);

        if ($inovasi->status_kurasi !== 'lolos_p3m') {
            return redirect()->route('dashboard')
                ->with('error', 'Hanya inovasi yang lolos P3M yang dapat ditetapkan harganya.');
        }

        $validated = $request->validate([
            'hpp'        => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|gte:hpp',
        ], [
            'hpp.required'        => 'HPP wajib diisi.',
            'hpp.numeric'         => 'HPP harus berupa angka.',
            'hpp.min'             => 'HPP tidak boleh bernilai negatif.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric'  => 'Harga jual harus berupa angka.',
            'harga_jual.gte'      => 'Harga jual harus lebih besar atau sama dengan HPP.',
        ]);

        $nilaiLama = [
            'hpp'        => $inovasi->hpp,
            'harga_jual' => $inovasi->harga_jual,
        ];

        $inovasi->update([
            'hpp'                   => $validated['hpp'],
            'harga_jual'            => $validated['harga_jual'],
            'harga_ditetapkan_oleh' => Auth::id(),
            'harga_ditetapkan_pada' => now(),
        ]);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'user_name'  => Auth::user()->name,
            'user_role'  => 'pimpinan',
            'aksi'       => 'SET_HARGA',
            'nama_tabel' => 'inovasi',
            'record_id'  => $inovasi->id,
            'nilai_lama' => $nilaiLama,
            'nilai_baru' => [
                'hpp'        => $validated['hpp'],
                'harga_jual' => $validated['harga_jual'],
            ],
            'deskripsi'  => 'Pimpinan menetapkan HPP: Rp ' . number_format($validated['hpp'], 0, ',', '.') . 
                            ' dan Harga Jual: Rp ' . number_format($validated['harga_jual'], 0, ',', '.') . 
                            ' untuk inovasi: ' . $inovasi->judul,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Harga untuk inovasi "' . $inovasi->judul . '" berhasil ditetapkan. Sekarang RTPU dapat mempublikasikan ke etalase.');
    }
}

