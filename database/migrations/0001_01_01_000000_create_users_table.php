<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Users (Pengguna Sistem PNJ PRIME)
 *
 * Menampung dua jenis pengguna:
 * - Internal (Dosen, Mahasiswa, P3M, RTPU, Pimpinan): Login via SSO kampus
 * - Eksternal (Masyarakat/Industri): Registrasi manual, butuh verifikasi RTPU
 */
return new class extends Migration
{
    public function up(): void
    {
        // Aktifkan ekstensi pgcrypto untuk gen_random_uuid()
        DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto"');

        Schema::create('users', function (Blueprint $table) {
            // Primary Key UUID
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            // === Identitas Utama ===
            $table->string('name');
            $table->string('email')->unique();

            // Role pengguna dalam sistem
            $table->enum('role', [
                'dosen',
                'mahasiswa',
                'p3m',
                'rtpu',
                'pimpinan',
                'eksternal',
            ]);

            // === Autentikasi ===
            // NULL untuk internal (login via SSO), diisi untuk eksternal
            $table->string('password_hash')->nullable();
            // Identifier dari SSO kampus (LDAP/ActiveDirectory), NULL untuk eksternal
            $table->string('sso_id', 100)->nullable()->unique()->comment('Identifier SSO kampus. Wajib untuk internal, NULL untuk eksternal.');

            // === Data Khusus Pengguna EKSTERNAL ===
            // Field berikut hanya diisi jika role = 'eksternal'
            $table->char('nik', 16)->nullable()->comment('Nomor Induk Kependudukan (16 digit)');
            $table->string('npwp', 20)->nullable()->comment('Nomor Pokok Wajib Pajak');
            $table->text('url_foto_ktp')->nullable()->comment('URL ke storage foto KTP (S3/MinIO)');
            $table->text('url_foto_npwp')->nullable()->comment('URL ke storage foto NPWP (S3/MinIO)');
            $table->string('nomor_telepon', 20)->nullable();
            $table->text('alamat')->nullable();

            // === Status Verifikasi (untuk Pengguna Eksternal) ===
            // Default 'menunggu' saat pertama kali mendaftar
            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'ditolak'])
                  ->default('menunggu')
                  ->comment('Status verifikasi akun eksternal oleh RTPU');
            // FK ke users (RTPU yang melakukan verifikasi) — ditambahkan setelah tabel dibuat
            $table->uuid('diverifikasi_oleh')->nullable()->comment('ID RTPU yang melakukan verifikasi');
            $table->timestampTz('diverifikasi_pada')->nullable();
            $table->text('catatan_verifikasi')->nullable()->comment('Alasan penolakan / catatan RTPU');

            // === Informasi Akun ===
            $table->boolean('is_active')->default(true);
            $table->timestampTz('email_verified_at')->nullable();
            $table->timestampTz('last_login_at')->nullable();
            $table->rememberToken();

            // Audit timestamps (TIMESTAMP WITH TIME ZONE)
            $table->timestampsTz();

            // Index
            $table->index('role');
            $table->index('status_verifikasi');
        });

        // FK self-referencing: diverifikasi_oleh → users.id
        // Harus ditambahkan SETELAH tabel selesai dibuat
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('diverifikasi_oleh')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });

        // Tabel pendukung Laravel Authentication
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestampTz('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        // Hapus FK self-referencing sebelum drop tabel
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['diverifikasi_oleh']);
        });

        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
