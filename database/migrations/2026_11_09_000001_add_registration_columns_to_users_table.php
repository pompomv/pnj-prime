<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom pendukung registrasi eksternal (Personal & Perusahaan)
 * dan SSO kampus pada tabel users.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Alias nama lengkap (users.name sudah ada, tambah alias untuk form)
            $table->string('nama_lengkap')->nullable()->after('name')
                  ->comment('Nama lengkap sesuai KTP / identitas resmi');

            // SSO: NIM / NIP / NIDN untuk login SSO kampus
            $table->string('nim_nip', 50)->nullable()->unique()->after('sso_id')
                  ->comment('Nomor Induk Mahasiswa, NIP, atau NIDN untuk login SSO');

            // Jenis akun eksternal: personal atau perusahaan
            $table->enum('jenis_akun', ['personal', 'perusahaan'])->nullable()->after('alamat')
                  ->comment('Jenis registrasi eksternal: personal/individu atau perusahaan');

            // Data khusus perusahaan
            $table->string('nama_perusahaan')->nullable()->after('jenis_akun')
                  ->comment('Nama resmi badan usaha / institusi');
            $table->char('nib', 13)->nullable()->after('nama_perusahaan')
                  ->comment('Nomor Induk Berusaha (13 digit, dari OSS)');

            // Dokumen perusahaan (path ke storage)
            $table->text('dok_npwp')->nullable()->after('url_foto_npwp')
                  ->comment('Path file NPWP perusahaan');
            $table->text('dok_nib_akta')->nullable()->after('dok_npwp')
                  ->comment('Path file NIB / Akta Perusahaan');

            // Path KTP dari registrasi (alias url_foto_ktp sebagai foto_ktp)
            $table->text('foto_ktp')->nullable()->after('dok_nib_akta')
                  ->comment('Path file foto KTP dari proses registrasi');
            $table->text('foto_npwp')->nullable()->after('foto_ktp')
                  ->comment('Path file foto NPWP dari proses registrasi');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nama_lengkap',
                'nim_nip',
                'jenis_akun',
                'nama_perusahaan',
                'nib',
                'dok_npwp',
                'dok_nib_akta',
                'foto_ktp',
                'foto_npwp',
            ]);
        });
    }
};

