<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;

    // Tabel ini tidak punya updated_at
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'id',
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'ref_tabel',
        'ref_id',
        'is_read',
        'dibaca_pada',
        'created_at',
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'dibaca_pada' => 'datetime',
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

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeBelumDibaca($query)
    {
        return $query->where('is_read', false);
    }

    // ─── Methods ─────────────────────────────────────────────────────────────

    public function markAsRead(): void
    {
        $this->update([
            'is_read'    => true,
            'dibaca_pada' => now(),
        ]);
    }
}
