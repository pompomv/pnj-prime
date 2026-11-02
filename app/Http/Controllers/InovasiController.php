<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Inovasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * InovasiController
 * Digunakan oleh Dosen & Mahasiswa untuk mengajukan, mengedit, dan menghapus inovasi.
 */
class InovasiController extends Controller
{
    // ── Form Ajukan Inovasi ───────────────────────────────────────────────────
    public function create()
    {
        return view('inovasi.create');
    }

    // ── Simpan Pengajuan Baru ─────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'                 => 'required|string|max:500',
            'deskripsi'             => 'required|string',
            'tipe_item'             => 'required|in:produk_fisik,jasa',
            'url_dokumen_pendukung' => 'nullable|url|max:2000',
            'url_foto_prototipe'    => 'nullable|url|max:2000',
            'stok'                  => 'nullable|integer|min:0',
            'satuan'                => 'nullable|string|max:50',
        ], [
            'judul.required'     => 'Judul inovasi wajib diisi.',
            'deskripsi.required' => 'Deskripsi inovasi wajib diisi.',
            'tipe_item.required' => 'Tipe item wajib dipilih.',
            'tipe_item.in'       => 'Tipe item tidak valid.',
        ]);

        $slug = Str::slug($validated['judul']) . '-' . Str::random(6);

        $inovasi = Inovasi::create([
            'pengaju_id'            => Auth::id(),
            'judul'                 => $validated['judul'],
            'slug'                  => $slug,
            'deskripsi'             => $validated['deskripsi'],
            'tipe_item'             => $validated['tipe_item'],
            'url_dokumen_pendukung' => $validated['url_dokumen_pendukung'] ?? null,
            'url_foto_prototipe'    => $validated['url_foto_prototipe'] ?? null,
            'stok'                  => $validated['tipe_item'] === 'produk_fisik' ? ($validated['stok'] ?? 0) : null,
            'satuan'                => $validated['satuan'] ?? ($validated['tipe_item'] === 'jasa' ? 'sesi' : 'pcs'),
            'status_kurasi'         => 'menunggu_validasi',
            'is_published'          => false,
        ]);

        // Catat ke audit log
        AuditLog::create([
            'user_id'    => Auth::id(),
            'user_name'  => Auth::user()->name,
            'user_role'  => Auth::user()->role,
            'aksi'       => 'INSERT',
            'nama_tabel' => 'inovasi',
            'record_id'  => $inovasi->id,
            'nilai_baru' => ['judul' => $inovasi->judul, 'tipe_item' => $inovasi->tipe_item],
            'deskripsi'  => 'Pengajuan inovasi baru: ' . $inovasi->judul,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Inovasi "' . $inovasi->judul . '" berhasil diajukan dan menunggu validasi P3M.');
    }

    // ── Detail Inovasi ────────────────────────────────────────────────────────
    public function show(string $id)
    {
        $inovasi = Inovasi::with(['pengaju', 'kurator', 'penetapHarga', 'publisher', 'media'])->findOrFail($id);

        // Hanya pengaju sendiri yang bisa edit
        $bisaEdit = Auth::user()->role !== 'eksternal'
            && $inovasi->pengaju_id === Auth::id()
            && in_array($inovasi->status_kurasi, ['menunggu_validasi', 'revisi']);

        return view('inovasi.show', compact('inovasi', 'bisaEdit'));
    }

    // ── Form Edit Inovasi ─────────────────────────────────────────────────────
    public function edit(string $id)
    {
        $inovasi = Inovasi::where('pengaju_id', Auth::id())->findOrFail($id);

        // Hanya bisa edit jika masih dalam status menunggu atau revisi
        if (!in_array($inovasi->status_kurasi, ['menunggu_validasi', 'revisi'])) {
            return redirect()->route('inovasi.show', $id)
                ->with('error', 'Inovasi tidak dapat diedit karena sudah dikurasi.');
        }

        return view('inovasi.edit', compact('inovasi'));
    }

    // ── Update Inovasi ────────────────────────────────────────────────────────
    public function update(Request $request, string $id)
    {
        $inovasi = Inovasi::where('pengaju_id', Auth::id())->findOrFail($id);

        if (!in_array($inovasi->status_kurasi, ['menunggu_validasi', 'revisi'])) {
            return redirect()->route('inovasi.show', $id)
                ->with('error', 'Inovasi tidak dapat diedit.');
        }

        $validated = $request->validate([
            'judul'                 => 'required|string|max:500',
            'deskripsi'             => 'required|string',
            'url_dokumen_pendukung' => 'nullable|url|max:2000',
            'url_foto_prototipe'    => 'nullable|url|max:2000',
            'stok'                  => 'nullable|integer|min:0',
            'satuan'                => 'nullable|string|max:50',
        ]);

        $nilaiLama = $inovasi->only(['judul', 'deskripsi', 'status_kurasi']);

        $inovasi->update([
            'judul'                 => $validated['judul'],
            'deskripsi'             => $validated['deskripsi'],
            'url_dokumen_pendukung' => $validated['url_dokumen_pendukung'] ?? null,
            'url_foto_prototipe'    => $validated['url_foto_prototipe'] ?? null,
            'stok'                  => $inovasi->tipe_item === 'produk_fisik' ? ($validated['stok'] ?? 0) : null,
            'satuan'                => $validated['satuan'] ?? $inovasi->satuan,
            // Jika sedang revisi, kembali ke menunggu_validasi setelah diedit
            'status_kurasi'         => $inovasi->status_kurasi === 'revisi' ? 'menunggu_validasi' : $inovasi->status_kurasi,
        ]);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'user_name'  => Auth::user()->name,
            'user_role'  => Auth::user()->role,
            'aksi'       => 'UPDATE',
            'nama_tabel' => 'inovasi',
            'record_id'  => $inovasi->id,
            'nilai_lama' => $nilaiLama,
            'nilai_baru' => $inovasi->only(['judul', 'deskripsi', 'status_kurasi']),
            'deskripsi'  => 'Pengaju memperbarui inovasi: ' . $inovasi->judul,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Inovasi berhasil diperbarui dan dikirim ulang untuk validasi P3M.');
    }

    // ── Hapus Inovasi ─────────────────────────────────────────────────────────
    public function destroy(string $id)
    {
        $inovasi = Inovasi::where('pengaju_id', Auth::id())->findOrFail($id);

        if (!in_array($inovasi->status_kurasi, ['menunggu_validasi', 'revisi'])) {
            return redirect()->route('dashboard')
                ->with('error', 'Inovasi tidak dapat dihapus karena sudah dikurasi.');
        }

        $judul = $inovasi->judul;
        $inovasi->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Inovasi "' . $judul . '" berhasil dihapus.');
    }
}

