<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;

    // Tabel ini tidak punya updated_at
    public $timestamps = false;

    protected $dates = ['created_at'];

    protected $fillable = [
        'id',
        'user_id',
        'user_name',
        'user_role',
        'aksi',
        'nama_tabel',
        'record_id',
        'nilai_lama',
        'nilai_baru',
        'deskripsi',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'nilai_lama' => 'array',
        'nilai_baru' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            if (empty($model->created_at)) {
                $model->created_at = now();
            }
        });
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Static Helper ───────────────────────────────────────────────────────

    /**
     * Catat aktivitas ke audit_logs.
     */
    public static function catat(
        ?User $user,
        string $aksi,
        string $tabel,
        ?string $recordId,
        ?array $lama,
        ?array $baru,
        ?string $deskripsi = null
    ): void {
        static::create([
            'user_id'    => $user?->id,
            'user_name'  => $user?->name,
            'user_role'  => $user?->role,
            'aksi'       => $aksi,
            'nama_tabel' => $tabel,
            'record_id'  => $recordId,
            'nilai_lama' => $lama,
            'nilai_baru' => $baru,
            'deskripsi'  => $deskripsi,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
