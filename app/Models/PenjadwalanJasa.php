<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PenjadwalanJasa extends Model
{
    use HasFactory;

    protected $table = 'penjadwalan_jasa';

    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'detail_transaksi_id',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'keterangan_jadwal',
        'status_jadwal',
        'url_bukti_pelaksanaan',
        'catatan_pelaksanaan',
        'dijadwalkan_oleh',
        'dijadwalkan_pada',
    ];

    protected $casts = [
        'waktu_mulai'     => 'datetime',
        'waktu_selesai'   => 'datetime',
        'dijadwalkan_pada' => 'datetime',
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

    public function detailTransaksi()
    {
        return $this->belongsTo(DetailTransaksi::class);
    }

    public function penjadwal()
    {
        return $this->belongsTo(User::class, 'dijadwalkan_oleh');
    }
}
