<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\DetailTransaksi;
use App\Models\Inovasi;
use App\Models\PenjadwalanJasa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * RtpuController
 * Digunakan oleh RTPU untuk verifikasi akun eksternal, kelola publikasi etalase, dan penjadwalan jasa.
 */
class RtpuController extends Controller
{
    // ── Verifikasi Akun Eksternal: Setujui ─────────────────────────────────────
    public function setujuiAkun(string $id)
    {
        $user = User::where('role', 'eksternal')->findOrFail($id);

        $user->update([
            'status_verifikasi'  => 'disetujui',
            'diverifikasi_oleh'  => Auth::id(),
            'diverifikasi_pada'  => now(),
            'catatan_verifikasi' => null,
        ]);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'user_name'  => Auth::user()->name,
            'user_role'  => 'rtpu',
            'aksi'       => 'VERIFIKASI_AKUN',
            'nama_tabel' => 'users',
            'record_id'  => $user->id,
            'nilai_lama' => ['status_verifikasi' => 'menunggu'],
            'nilai_baru' => ['status_verifikasi' => 'disetujui'],
            'deskripsi'  => 'RTPU menyetujui akun eksternal: ' . $user->name . ' (' . $user->email . ')',
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Akun eksternal "' . $user->name . '" telah disetujui.');
    }

    // ── Verifikasi Akun Eksternal: Tolak ───────────────────────────────────────
    public function tolakAkun(Request $request, string $id)
    {
        $request->validate([
            'catatan_verifikasi' => 'required|string|min:5|max:1000',
        ], [
            'catatan_verifikasi.required' => 'Alasan penolakan akun wajib diisi.',
            'catatan_verifikasi.min'      => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $user = User::where('role', 'eksternal')->findOrFail($id);

        $user->update([
            'status_verifikasi'  => 'ditolak',
            'diverifikasi_oleh'  => Auth::id(),
            'diverifikasi_pada'  => now(),
            'catatan_verifikasi' => $request->catatan_verifikasi,
        ]);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'user_name'  => Auth::user()->name,
            'user_role'  => 'rtpu',
            'aksi'       => 'VERIFIKASI_AKUN',
            'nama_tabel' => 'users',
            'record_id'  => $user->id,
            'nilai_lama' => ['status_verifikasi' => 'menunggu'],
            'nilai_baru' => [
                'status_verifikasi'  => 'ditolak',
                'catatan_verifikasi' => $request->catatan_verifikasi
            ],
            'deskripsi'  => 'RTPU menolak akun eksternal: ' . $user->name . '. Alasan: ' . $request->catatan_verifikasi,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Akun eksternal "' . $user->name . '" telah ditolak.');
    }

    // ── Form Publikasi Inovasi ke Etalase ─────────────────────────────────────
    public function formPublikasi(string $id)
    {
        $inovasi = Inovasi::with(['pengaju', 'kurator', 'penetapHarga'])->findOrFail($id);

        if ($inovasi->status_kurasi !== 'lolos_p3m' || empty($inovasi->harga_jual)) {
            return redirect()->route('dashboard')
                ->with('error', 'Inovasi harus lolos kurasi P3M dan telah ditetapkan harganya oleh Pimpinan.');
        }

        return view('rtpu.publikasi', compact('inovasi'));
    }

    // ── Simpan Status Publikasi ───────────────────────────────────────────────
    public function publikasi(Request $request, string $id)
    {
        $inovasi = Inovasi::findOrFail($id);

        if ($inovasi->status_kurasi !== 'lolos_p3m' || empty($inovasi->harga_jual)) {
            return redirect()->route('dashboard')
                ->with('error', 'Inovasi belum memenuhi syarat publikasi.');
        }

        $validated = $request->validate([
            'is_published'      => 'required|boolean',
            'deskripsi_promosi' => 'nullable|string',
        ]);

        $aksi = $validated['is_published'] ? 'PUBLISH' : 'UNPUBLISH';

        $inovasi->update([
            'is_published'      => $validated['is_published'],
            'deskripsi_promosi' => $validated['deskripsi_promosi'] ?? $inovasi->deskripsi_promosi,
            'dipublikasi_oleh'  => Auth::id(),
            'dipublikasi_pada'  => now(),
        ]);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'user_name'  => Auth::user()->name,
            'user_role'  => 'rtpu',
            'aksi'       => $aksi,
            'nama_tabel' => 'inovasi',
            'record_id'  => $inovasi->id,
            'nilai_baru' => [
                'is_published'      => $validated['is_published'],
                'deskripsi_promosi' => $validated['deskripsi_promosi'] ?? null,
            ],
            'deskripsi'  => 'RTPU mengubah status publikasi inovasi: ' . $inovasi->judul . ' menjadi ' . ($validated['is_published'] ? 'PUBLIK (Aktif di Etalase)' : 'DRAFT / DIHAPUS DARI ETALASE'),
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Status publikasi inovasi "' . $inovasi->judul . '" berhasil diperbarui.');
    }

    // ── Form Penjadwalan Jasa ─────────────────────────────────────────────────
    public function formJadwal(string $detailId)
    {
        $detail = DetailTransaksi::with(['inovasi', 'transaksi.pembeli', 'jadwal'])->findOrFail($detailId);

        return view('rtpu.jadwal_form', compact('detail'));
    }

    // ── Simpan Jadwal Jasa ───────────────────────────────────────────────────
    public function simpanJadwal(Request $request, string $detailId)
    {
        $detail = DetailTransaksi::findOrFail($detailId);

        $validated = $request->validate([
            'waktu_mulai'       => 'required|date|after_or_equal:now',
            'waktu_selesai'     => 'required|date|after:waktu_mulai',
            'lokasi'            => 'required|string|max:255',
            'keterangan_jadwal' => 'nullable|string',
        ], [
            'waktu_mulai.required'   => 'Waktu mulai pelaksanaan wajib diisi.',
            'waktu_selesai.required' => 'Waktu selesai wajib diisi.',
            'waktu_selesai.after'    => 'Waktu selesai harus setelah waktu mulai.',
            'lokasi.required'        => 'Lokasi ruangan/laboratorium wajib diisi.',
        ]);

        $jadwal = PenjadwalanJasa::updateOrCreate(
            ['detail_transaksi_id' => $detail->id],
            [
                'waktu_mulai'       => $validated['waktu_mulai'],
                'waktu_selesai'     => $validated['waktu_selesai'],
                'lokasi'            => $validated['lokasi'],
                'keterangan_jadwal' => $validated['keterangan_jadwal'] ?? null,
                'status_jadwal'     => 'terjadwal',
                'dijadwalkan_oleh'  => Auth::id(),
                'dijadwalkan_pada'  => now(),
            ]
        );

        AuditLog::create([
            'user_id'    => Auth::id(),
            'user_name'  => Auth::user()->name,
            'user_role'  => 'rtpu',
            'aksi'       => 'UPDATE',
            'nama_tabel' => 'penjadwalan_jasa',
            'record_id'  => $jadwal->id,
            'nilai_baru' => $jadwal->only(['status_jadwal', 'waktu_mulai', 'waktu_selesai', 'lokasi']),
            'deskripsi'  => 'RTPU menjadwalkan jasa: ' . $detail->judul_saat_transaksi . ' di ' . $validated['lokasi'],
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Jadwal pelaksanaan jasa telah berhasil ditetapkan.');
    }

    // ── Tandai Jasa Selesai & Upload Bukti ─────────────────────────────────────
    public function selesaiJadwal(Request $request, string $jadwalId)
    {
        $jadwal = PenjadwalanJasa::findOrFail($jadwalId);

        $validated = $request->validate([
            'url_bukti_pelaksanaan' => 'required|url|max:2000',
            'catatan_pelaksanaan'   => 'nullable|string',
        ], [
            'url_bukti_pelaksanaan.required' => 'URL bukti pelaksanaan (foto/dokumen) wajib disertakan.',
            'url_bukti_pelaksanaan.url'      => 'URL bukti pelaksanaan harus berformat URL yang valid.',
        ]);

        $jadwal->update([
            'status_jadwal'         => 'selesai',
            'url_bukti_pelaksanaan' => $validated['url_bukti_pelaksanaan'],
            'catatan_pelaksanaan'   => $validated['catatan_pelaksanaan'] ?? null,
        ]);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'user_name'  => Auth::user()->name,
            'user_role'  => 'rtpu',
            'aksi'       => 'UPDATE',
            'nama_tabel' => 'penjadwalan_jasa',
            'record_id'  => $jadwal->id,
            'nilai_baru' => ['status_jadwal' => 'selesai'],
            'deskripsi'  => 'RTPU menandai pelaksanaan jasa selesai dengan bukti: ' . $validated['url_bukti_pelaksanaan'],
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Jasa telah ditandai selesai dilaksanakan.');
    }
}

