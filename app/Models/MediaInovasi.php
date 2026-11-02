<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MediaInovasi extends Model
{
    protected $table = 'media_inovasi';

    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;

    // Tabel ini tidak punya updated_at
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'id',
        'inovasi_id',
        'url_media',
        'tipe_media',
        'keterangan',
        'urutan',
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

    public function inovasi()
    {
        return $this->belongsTo(Inovasi::class);
    }
}
