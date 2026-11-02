<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Inovasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * KurasiController
 * Digunakan oleh P3M untuk memvalidasi, meminta revisi, atau menolak inovasi.
 */
class KurasiController extends Controller
{
    // ── Detail Inovasi untuk Dikurasi ─────────────────────────────────────────
    public function show(string $id)
    {
        $inovasi = Inovasi::with(['pengaju', 'media'])->findOrFail($id);
        return view('kurasi.show', compact('inovasi'));
    }

    // ── Setujui (Lolos P3M) ───────────────────────────────────────────────────
    public function setujui(string $id)
    {
        $inovasi = Inovasi::whereIn('status_kurasi', ['menunggu_validasi', 'revisi'])->findOrFail($id);

        $nilaiLama = ['status_kurasi' => $inovasi->status_kurasi];

        $inovasi->update([
            'status_kurasi'  => 'lolos_p3m',
            'dikurasi_oleh'  => Auth::id(),
            'dikurasi_pada'  => now(),
            'catatan_revisi' => null,
        ]);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'user_name'  => Auth::user()->name,
            'user_role'  => 'p3m',
            'aksi'       => 'APPROVE',
            'nama_tabel' => 'inovasi',
            'record_id'  => $inovasi->id,
            'nilai_lama' => $nilaiLama,
            'nilai_baru' => ['status_kurasi' => 'lolos_p3m'],
            'deskripsi'  => 'P3M menyetujui inovasi: ' . $inovasi->judul,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Inovasi "' . $inovasi->judul . '" berhasil disetujui. Menunggu penetapan harga oleh Pimpinan.');
    }

    // ── Minta Revisi ──────────────────────────────────────────────────────────
    public function revisi(Request $request, string $id)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|min:10|max:1000',
        ], [
            'catatan_revisi.required' => 'Catatan revisi wajib diisi.',
            'catatan_revisi.min'      => 'Catatan revisi minimal 10 karakter.',
        ]);

        $inovasi = Inovasi::whereIn('status_kurasi', ['menunggu_validasi'])->findOrFail($id);

        $nilaiLama = ['status_kurasi' => $inovasi->status_kurasi];

        $inovasi->update([
            'status_kurasi'  => 'revisi',
            'dikurasi_oleh'  => Auth::id(),
            'dikurasi_pada'  => now(),
            'catatan_revisi' => $request->catatan_revisi,
        ]);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'user_name'  => Auth::user()->name,
            'user_role'  => 'p3m',
            'aksi'       => 'REVISI',
            'nama_tabel' => 'inovasi',
            'record_id'  => $inovasi->id,
            'nilai_lama' => $nilaiLama,
            'nilai_baru' => ['status_kurasi' => 'revisi', 'catatan_revisi' => $request->catatan_revisi],
            'deskripsi'  => 'P3M meminta revisi untuk inovasi: ' . $inovasi->judul,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Permintaan revisi untuk inovasi "' . $inovasi->judul . '" berhasil dikirim ke pengaju.');
    }

    // ── Tolak Inovasi ─────────────────────────────────────────────────────────
    public function tolak(Request $request, string $id)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|min:10|max:1000',
        ], [
            'catatan_revisi.required' => 'Alasan penolakan wajib diisi.',
            'catatan_revisi.min'      => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $inovasi = Inovasi::whereIn('status_kurasi', ['menunggu_validasi', 'revisi'])->findOrFail($id);

        $nilaiLama = ['status_kurasi' => $inovasi->status_kurasi];

        $inovasi->update([
            'status_kurasi'  => 'ditolak',
            'dikurasi_oleh'  => Auth::id(),
            'dikurasi_pada'  => now(),
            'catatan_revisi' => $request->catatan_revisi,
        ]);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'user_name'  => Auth::user()->name,
            'user_role'  => 'p3m',
            'aksi'       => 'REJECT',
            'nama_tabel' => 'inovasi',
            'record_id'  => $inovasi->id,
            'nilai_lama' => $nilaiLama,
            'nilai_baru' => ['status_kurasi' => 'ditolak', 'catatan_revisi' => $request->catatan_revisi],
            'deskripsi'  => 'P3M menolak inovasi: ' . $inovasi->judul,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Inovasi "' . $inovasi->judul . '" ditolak.');
    }
}

