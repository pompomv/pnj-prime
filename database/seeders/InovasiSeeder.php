<?php

namespace Database\Seeders;

use App\Models\Inovasi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InovasiSeeder extends Seeder
{
    public function run(): void
    {
        $dosen     = User::where('email', 'dosen@pnj.ac.id')->first();
        $mahasiswa = User::where('email', 'mahasiswa@pnj.ac.id')->first();
        $p3m       = User::where('email', 'p3m@pnj.ac.id')->first();
        $pimpinan  = User::where('email', 'pimpinan@pnj.ac.id')->first();
        $rtpu      = User::where('email', 'rtpu@pnj.ac.id')->first();
        $now       = now();

        $inovasiList = [
            [
                'id'                    => Str::uuid()->toString(),
                'pengaju_id'            => $dosen->id,
                'judul'                 => 'Alat Ukur Kualitas Air Portabel',
                'slug'                  => 'alat-ukur-kualitas-air-portabel-' . Str::random(6),
                'deskripsi'             => 'Alat ukur kualitas air portabel yang dapat mengukur pH, suhu, dan kekeruhan air secara real-time.',
                'tipe_item'             => 'produk_fisik',
                'status_kurasi'         => 'lolos_p3m',
                'dikurasi_oleh'         => $p3m->id,
                'dikurasi_pada'         => $now,
                'hpp'                   => 500000.00,
                'harga_jual'            => 750000.00,
                'harga_ditetapkan_oleh' => $pimpinan->id,
                'harga_ditetapkan_pada' => $now,
                'is_published'          => true,
                'deskripsi_promosi'     => 'Solusi terbaik untuk memantau kualitas air di lapangan.',
                'dipublikasi_oleh'      => $rtpu->id,
                'dipublikasi_pada'      => $now,
                'stok'                  => 10,
                'satuan'                => 'unit',
                'created_at'            => $now,
                'updated_at'            => $now,
            ],
            [
                'id'                    => Str::uuid()->toString(),
                'pengaju_id'            => $dosen->id,
                'judul'                 => 'Jasa Pengujian Kekuatan Material',
                'slug'                  => 'jasa-pengujian-kekuatan-material-' . Str::random(6),
                'deskripsi'             => 'Layanan pengujian kekuatan material menggunakan peralatan laboratorium berstandar nasional.',
                'tipe_item'             => 'jasa',
                'status_kurasi'         => 'lolos_p3m',
                'dikurasi_oleh'         => $p3m->id,
                'dikurasi_pada'         => $now,
                'hpp'                   => 200000.00,
                'harga_jual'            => 350000.00,
                'harga_ditetapkan_oleh' => $pimpinan->id,
                'harga_ditetapkan_pada' => $now,
                'is_published'          => true,
                'deskripsi_promosi'     => 'Pengujian material terpercaya untuk kebutuhan industri dan penelitian.',
                'dipublikasi_oleh'      => $rtpu->id,
                'dipublikasi_pada'      => $now,
                'stok'                  => null,
                'satuan'                => 'sesi',
                'created_at'            => $now,
                'updated_at'            => $now,
            ],
            [
                'id'                    => Str::uuid()->toString(),
                'pengaju_id'            => $mahasiswa->id,
                'judul'                 => 'Prototype Robot Agrikultur',
                'slug'                  => 'prototype-robot-agrikultur-' . Str::random(6),
                'deskripsi'             => 'Prototype robot agrikultur yang dirancang untuk membantu proses penanaman dan panen secara otomatis.',
                'tipe_item'             => 'produk_fisik',
                'status_kurasi'         => 'menunggu_validasi',
                'is_published'          => false,
                'stok'                  => 2,
                'satuan'                => 'unit',
                'created_at'            => $now,
                'updated_at'            => $now,
            ],
        ];

        foreach ($inovasiList as $data) {
            Inovasi::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
