<?php

namespace Database\Seeders;

use App\Models\RekeningBlu;
use Illuminate\Database\Seeder;

class RekeningBluSeeder extends Seeder
{
    public function run(): void
    {
        RekeningBlu::insert([
            [
                'id'             => \Illuminate\Support\Str::uuid()->toString(),
                'nama_bank'      => 'Bank Mandiri',
                'nama_rekening'  => 'PNJ - BLU',
                'nomor_rekening' => '1234567890',
                'keterangan'     => 'Rekening utama BLU PNJ',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'id'             => \Illuminate\Support\Str::uuid()->toString(),
                'nama_bank'      => 'BNI',
                'nama_rekening'  => 'PNJ - BLU Alternatif',
                'nomor_rekening' => '0987654321',
                'keterangan'     => 'Rekening alternatif BLU PNJ',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
