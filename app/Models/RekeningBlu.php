<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RekeningBlu extends Model
{
    use HasFactory;

    protected $table = 'rekening_blu';

    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_bank',
        'nama_rekening',
        'nomor_rekening',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
