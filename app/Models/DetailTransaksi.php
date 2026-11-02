<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi';

    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;

    // Tabel ini tidak punya updated_at
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'id',
        'transaksi_id',
        'inovasi_id',
        'judul_saat_transaksi',
        'tipe_item',
        'kuantitas',
        'harga_saat_transaksi',
        'subtotal',
        'catatan',
    ];

    protected $casts = [
        'harga_saat_transaksi' => 'decimal:2',
        'subtotal'             => 'decimal:2',
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

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function inovasi()
    {
        return $this->belongsTo(Inovasi::class);
    }

    public function jadwal()
    {
        return $this->hasOne(PenjadwalanJasa::class);
    }
}
