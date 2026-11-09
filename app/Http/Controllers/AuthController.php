<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ─── Show Login ────────────────────────────────────────────────────────
    public function showLogin()
    {
        return view('auth.login');
    }

    // ─── Login (Eksternal) ─────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password_hash)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau kata sandi salah.']);
        }

        if (! $user->is_active) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun Anda sedang dalam proses verifikasi atau tidak aktif. Hubungi administrator.']);
        }

        Auth::login($user, $request->boolean('remember'));

        $user->update(['last_login_at' => now()]);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    // ─── Show SSO Login ────────────────────────────────────────────────────
    public function showSso()
    {
        return view('auth.sso');
    }

    // ─── SSO Login (simulasi — real SSO via API kampus belum aktif) ────────
    public function ssoLogin(Request $request)
    {
        $request->validate([
            'nim_nip'      => 'required|string|min:8',
            'password_sso' => 'required|string|min:6',
        ], [
            'nim_nip.required'      => 'NIM/NIP/NIDN wajib diisi.',
            'nim_nip.min'           => 'NIM/NIP/NIDN minimal 8 karakter.',
            'password_sso.required' => 'Kata sandi SSO wajib diisi.',
        ]);

        // Cari user berdasarkan NIM/NIP, SSO ID, atau email
        $user = User::where(function ($q) use ($request) {
                        $q->where('nim_nip', $request->nim_nip)
                          ->orWhere('sso_id', $request->nim_nip)
                          ->orWhere('email', $request->nim_nip);
                    })
                    ->whereIn('role', ['dosen', 'mahasiswa', 'p3m', 'rtpu', 'pimpinan'])
                    ->first();

        if (! $user || ! Hash::check($request->password_sso, $user->password_hash)) {
            return back()
                ->withInput($request->only('nim_nip'))
                ->withErrors(['nim_nip' => 'NIM/NIP/NIDN atau kata sandi SSO tidak sesuai.']);
        }

        if (! $user->is_active) {
            return back()
                ->withInput($request->only('nim_nip'))
                ->withErrors(['nim_nip' => 'Akun tidak aktif. Hubungi UPT TIK.']);
        }

        Auth::login($user, $request->boolean('remember_device'));

        $user->update(['last_login_at' => now()]);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    // ─── Show Register ─────────────────────────────────────────────────────
    public function showRegister()
    {
        return view('auth.register');
    }

    // ─── Register Personal ─────────────────────────────────────────────────
    public function registerPersonal(Request $request)
    {
        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'nik'           => 'required|digits:16',
            'npwp'          => 'nullable|digits:16',
            'foto_ktp'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'foto_npwp'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'agree_terms'   => 'accepted',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar. Silakan masuk atau gunakan email lain.',
            'password.required'     => 'Kata sandi wajib diisi.',
            'password.min'          => 'Kata sandi minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi kata sandi tidak cocok.',
            'nik.required'          => 'NIK wajib diisi.',
            'nik.digits'            => 'NIK harus tepat 16 digit angka.',
            'foto_ktp.required'     => 'Foto KTP/Paspor wajib diunggah.',
            'foto_ktp.mimes'        => 'File KTP harus berformat JPG, PNG, atau PDF.',
            'foto_ktp.max'          => 'Ukuran file KTP maksimal 5 MB.',
            'agree_terms.accepted'  => 'Anda harus menyetujui Syarat & Ketentuan.',
        ]);

        $fotoKtpPath  = $request->file('foto_ktp')->store('dokumen/ktp', 'public');
        $fotoNpwpPath = $request->hasFile('foto_npwp')
                        ? $request->file('foto_npwp')->store('dokumen/npwp', 'public')
                        : null;

        $user = User::create([
            'id'                => Str::uuid(),
            'name'              => $request->nama_lengkap,
            'nama_lengkap'      => $request->nama_lengkap,
            'email'             => $request->email,
            'password_hash'     => Hash::make($request->password),
            'role'              => 'eksternal',
            'is_active'         => false, // Menunggu verifikasi RTPU
            'status_verifikasi' => 'menunggu',
            'nik'               => $request->nik,
            'npwp'              => $request->npwp,
            'foto_ktp'          => $fotoKtpPath,
            'url_foto_ktp'      => $fotoKtpPath,
            'foto_npwp'         => $fotoNpwpPath,
            'url_foto_npwp'     => $fotoNpwpPath,
            'jenis_akun'        => 'personal',
        ]);

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil! Akun Anda sedang dalam proses verifikasi oleh RTPU. Kami akan menghubungi Anda via email.');
    }

    // ─── Register Perusahaan ───────────────────────────────────────────────
    public function registerPerusahaan(Request $request)
    {
        $request->validate([
            'nama_pic'          => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:8|confirmed',
            'nama_perusahaan'   => 'required|string|max:255',
            'nib'               => 'required|digits:13',
            'npwp_perusahaan'   => 'required|digits:16',
            'alamat'            => 'required|string',
            'dok_npwp'          => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'dok_nib_akta'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'agree_disclaimer'  => 'accepted',
        ], [
            'nama_pic.required'         => 'Nama PIC wajib diisi.',
            'email.required'            => 'Email wajib diisi.',
            'email.email'               => 'Format email tidak valid.',
            'email.unique'              => 'Email sudah terdaftar.',
            'password.required'         => 'Kata sandi wajib diisi.',
            'password.min'              => 'Kata sandi minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi kata sandi tidak cocok.',
            'nama_perusahaan.required'  => 'Nama perusahaan wajib diisi.',
            'nib.required'              => 'NIB wajib diisi.',
            'nib.digits'                => 'NIB harus tepat 13 digit.',
            'npwp_perusahaan.required'  => 'NPWP perusahaan wajib diisi.',
            'npwp_perusahaan.digits'    => 'NPWP harus tepat 16 digit.',
            'alamat.required'           => 'Alamat kantor wajib diisi.',
            'dok_npwp.required'         => 'Dokumen NPWP perusahaan wajib diunggah.',
            'dok_nib_akta.required'     => 'Dokumen NIB/Akta wajib diunggah.',
            'agree_disclaimer.accepted' => 'Anda harus mencentang pernyataan kebenaran data.',
        ]);

        $dokNpwpPath   = $request->file('dok_npwp')->store('dokumen/perusahaan/npwp', 'public');
        $dokNibPath    = $request->file('dok_nib_akta')->store('dokumen/perusahaan/nib', 'public');

        $user = User::create([
            'id'                => Str::uuid(),
            'name'              => $request->nama_pic,
            'nama_lengkap'      => $request->nama_pic,
            'email'             => $request->email,
            'password_hash'     => Hash::make($request->password),
            'role'              => 'eksternal',
            'is_active'         => false, // Menunggu verifikasi RTPU
            'status_verifikasi' => 'menunggu',
            'nama_perusahaan'   => $request->nama_perusahaan,
            'nib'               => $request->nib,
            'npwp'              => $request->npwp_perusahaan,
            'alamat'            => $request->alamat,
            'dok_npwp'          => $dokNpwpPath,
            'dok_nib_akta'      => $dokNibPath,
            'jenis_akun'        => 'perusahaan',
        ]);

        return redirect()->route('login')
            ->with('success', 'Pendaftaran perusahaan berhasil! Dokumen Anda sedang diverifikasi oleh tim RTPU. Proses verifikasi 1-3 hari kerja.');
    }

    // ─── Logout ────────────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
