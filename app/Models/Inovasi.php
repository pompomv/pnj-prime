<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Inovasi extends Model
{
    use HasFactory;

    protected $table = 'inovasi';

    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'pengaju_id',
        'judul',
        'slug',
        'deskripsi',
        'tipe_item',
        'url_dokumen_pendukung',
        'url_foto_prototipe',
        'status_kurasi',
        'dikurasi_oleh',
        'dikurasi_pada',
        'catatan_revisi',
        'hpp',
        'harga_jual',
        'harga_ditetapkan_oleh',
        'harga_ditetapkan_pada',
        'is_published',
        'deskripsi_promosi',
        'dipublikasi_oleh',
        'dipublikasi_pada',
        'stok',
        'satuan',
    ];

    protected $casts = [
        'hpp'                   => 'decimal:2',
        'harga_jual'            => 'decimal:2',
        'is_published'          => 'boolean',
        'dikurasi_pada'         => 'datetime',
        'harga_ditetapkan_pada' => 'datetime',
        'dipublikasi_pada'      => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->judul) . '-' . Str::random(6);
            }
        });
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'pengaju_id');
    }

    public function kurator()
    {
        return $this->belongsTo(User::class, 'dikurasi_oleh');
    }

    public function penetapHarga()
    {
        return $this->belongsTo(User::class, 'harga_ditetapkan_oleh');
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'dipublikasi_oleh');
    }

    public function media()
    {
        return $this->hasMany(MediaInovasi::class);
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByTipe($query, string $tipe)
    {
        return $query->where('tipe_item', $tipe);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status_kurasi', $status);
    }

    public function scopeMenungguKurasi($query)
    {
        return $query->where('status_kurasi', 'menunggu_validasi');
    }

    public function scopeProdukFisik($query)
    {
        return $query->where('tipe_item', 'produk_fisik');
    }

    public function scopeJasa($query)
    {
        return $query->where('tipe_item', 'jasa');
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->harga_jual, 0, ',', '.');
    }

    public function getHppFormatAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->hpp, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_kurasi) {
            'menunggu_validasi' => 'Menunggu Validasi',
            'revisi'            => 'Perlu Revisi',
            'ditolak'           => 'Ditolak',
            'lolos_p3m'         => 'Lolos P3M',
            default             => ucfirst($this->status_kurasi ?? '-'),
        };
    }

    public function getTipeLabelAttribute(): string
    {
        return match ($this->tipe_item) {
            'produk_fisik' => 'Produk Fisik',
            'jasa'         => 'Jasa',
            default        => ucfirst($this->tipe_item ?? '-'),
        };
    }
}
