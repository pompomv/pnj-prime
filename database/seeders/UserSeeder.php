<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $users = [
            [
                'id'                 => Str::uuid()->toString(),
                'name'               => 'Dr. Budi Santoso, M.T.',
                'nama_lengkap'       => 'Dr. Budi Santoso, M.T.',
                'email'              => 'dosen@pnj.ac.id',
                'role'               => 'dosen',
                'password_hash'      => Hash::make('password123'),
                'sso_id'             => 'NIP001',
                'nim_nip'            => '197501012000031001',
                'status_verifikasi'  => 'disetujui',
                'is_active'          => true,
                'email_verified_at'  => $now,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                'id'                 => Str::uuid()->toString(),
                'name'               => 'Andi Pratama',
                'nama_lengkap'       => 'Andi Pratama',
                'email'              => 'mahasiswa@pnj.ac.id',
                'role'               => 'mahasiswa',
                'password_hash'      => Hash::make('password123'),
                'sso_id'             => 'NIM001',
                'nim_nip'            => '2107411001',
                'status_verifikasi'  => 'disetujui',
                'is_active'          => true,
                'email_verified_at'  => $now,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                'id'                 => Str::uuid()->toString(),
                'name'               => 'Staff P3M',
                'nama_lengkap'       => 'Staff P3M',
                'email'              => 'p3m@pnj.ac.id',
                'role'               => 'p3m',
                'password_hash'      => Hash::make('password123'),
                'sso_id'             => 'P3M001',
                'nim_nip'            => '198002022005011002',
                'status_verifikasi'  => 'disetujui',
                'is_active'          => true,
                'email_verified_at'  => $now,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                'id'                 => Str::uuid()->toString(),
                'name'               => 'Staff RTPU',
                'nama_lengkap'       => 'Staff RTPU',
                'email'              => 'rtpu@pnj.ac.id',
                'role'               => 'rtpu',
                'password_hash'      => Hash::make('password123'),
                'sso_id'             => 'RTPU001',
                'nim_nip'            => '198503032010121003',
                'status_verifikasi'  => 'disetujui',
                'is_active'          => true,
                'email_verified_at'  => $now,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                'id'                 => Str::uuid()->toString(),
                'name'               => 'Dr. Direktur PNJ',
                'nama_lengkap'       => 'Dr. Direktur PNJ',
                'email'              => 'pimpinan@pnj.ac.id',
                'role'               => 'pimpinan',
                'password_hash'      => Hash::make('password123'),
                'sso_id'             => 'PIM001',
                'nim_nip'            => '196804041993031004',
                'status_verifikasi'  => 'disetujui',
                'is_active'          => true,
                'email_verified_at'  => $now,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                'id'                 => Str::uuid()->toString(),
                'name'               => 'Budi Industri',
                'nama_lengkap'       => 'Budi Industri',
                'email'              => 'eksternal@gmail.com',
                'role'               => 'eksternal',
                'jenis_akun'         => 'personal',
                'password_hash'      => Hash::make('password123'),
                'nik'                => '3201234567890001',
                'npwp'               => '12.345.678.9-001.000',
                'nomor_telepon'      => '081234567890',
                'alamat'             => 'Jl. Industri No. 1, Jakarta',
                'status_verifikasi'  => 'disetujui',
                'is_active'          => true,
                'email_verified_at'  => $now,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
