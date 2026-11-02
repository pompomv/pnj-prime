<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'pembeli_id',
        'nomor_order',
        'subtotal',
        'biaya_admin',
        'total_harga',
        'metode_pembayaran',
        'status_pembayaran',
        'rekening_blu_id',
        'url_bukti_pembayaran',
        'dikonfirmasi_oleh',
        'dikonfirmasi_pada',
        'catatan_pembeli',
        'catatan_admin',
        'batas_waktu_bayar',
    ];

    protected $casts = [
        'subtotal'         => 'decimal:2',
        'biaya_admin'      => 'decimal:2',
        'total_harga'      => 'decimal:2',
        'dikonfirmasi_pada' => 'datetime',
        'batas_waktu_bayar' => 'datetime',
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

    // ─── Relationships ───────────────────────────────────────────────────────

    public function pembeli()
    {
        return $this->belongsTo(User::class, 'pembeli_id');
    }

    public function rekeningBlu()
    {
        return $this->belongsTo(RekeningBlu::class);
    }

    public function dikonfirmasiOleh()
    {
        return $this->belongsTo(User::class, 'dikonfirmasi_oleh');
    }

    public function items()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status_pembayaran', $status);
    }

    public function scopeLunas($query)
    {
        return $query->where('status_pembayaran', 'lunas');
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status_pembayaran', 'menunggu_pembayaran');
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getTotalFormatAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->total_harga, 0, ',', '.');
    }
}
