<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'role',
        'password_hash',
        'sso_id',
        // SSO kampus
        'nim_nip',
        // Identitas
        'nama_lengkap',
        'nik',
        'npwp',
        'url_foto_ktp',
        'url_foto_npwp',
        'foto_ktp',
        'foto_npwp',
        'nomor_telepon',
        'alamat',
        // Registrasi eksternal
        'jenis_akun',
        'nama_perusahaan',
        'nib',
        'dok_npwp',
        'dok_nib_akta',
        // Status
        'status_verifikasi',
        'diverifikasi_oleh',
        'diverifikasi_pada',
        'catatan_verifikasi',
        'is_active',
        'email_verified_at',
        'last_login_at',
        'remember_token',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $casts = [
        'is_active'          => 'boolean',
        'email_verified_at'  => 'datetime',
        'diverifikasi_pada'  => 'datetime',
        'last_login_at'      => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // ─── Auth ────────────────────────────────────────────────────────────────

    /**
     * Override karena kolom password bernama password_hash.
     */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    // ─── Filament ────────────────────────────────────────────────────────────

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['rtpu', 'p3m', 'pimpinan'], true);
    }

    // ─── Role Helpers ────────────────────────────────────────────────────────

    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function isP3m(): bool
    {
        return $this->role === 'p3m';
    }

    public function isRtpu(): bool
    {
        return $this->role === 'rtpu';
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

    public function isEksternal(): bool
    {
        return $this->role === 'eksternal';
    }

    public function isInternal(): bool
    {
        return $this->role !== 'eksternal';
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    public function inovasiDiajukan()
    {
        return $this->hasMany(Inovasi::class, 'pengaju_id');
    }

    public function inovasiDikurasi()
    {
        return $this->hasMany(Inovasi::class, 'dikurasi_oleh');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'pembeli_id');
    }

    public function penjadwalanDibuat()
    {
        return $this->hasMany(PenjadwalanJasa::class, 'dijadwalkan_oleh');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * RTPU yang memverifikasi user ini.
     */
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    /**
     * User-user yang telah diverifikasi oleh user ini.
     */
    public function penggunaYangDiverifikasi()
    {
        return $this->hasMany(User::class, 'diverifikasi_oleh');
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeEksternal($query)
    {
        return $query->where('role', 'eksternal');
    }

    public function scopeInternal($query)
    {
        return $query->where('role', '!=', 'eksternal');
    }

    public function scopeMenungguVerifikasi($query)
    {
        return $query->where('status_verifikasi', 'menunggu');
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        $colors = [
            'dosen'     => '1e40af',
            'mahasiswa' => '065f46',
            'p3m'       => '92400e',
            'rtpu'      => '4c1d95',
            'pimpinan'  => '7f1d1d',
            'eksternal' => '374151',
        ];

        $color      = $colors[$this->role] ?? '374151';
        $name       = urlencode($this->name ?? 'User');
        return "https://ui-avatars.com/api/?name={$name}&background={$color}&color=fff&size=128";
    }
}
