<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Audit Logs (Jejak Rekam Aksi)
 *
 * Merekam semua aksi krusial dalam sistem secara immutable
 * (hanya INSERT, tidak pernah UPDATE atau DELETE).
 *
 * Aksi yang wajib dicatat:
 *   - SET_HARGA     : Pimpinan menetapkan HPP & Harga Jual
 *   - APPROVE/REJECT: P3M mengkurasi inovasi
 *   - REVISI        : P3M meminta revisi ke pengaju
 *   - PUBLISH       : RTPU mempublikasikan inovasi
 *   - VERIFIKASI_AKUN: RTPU menyetujui/menolak akun eksternal
 *   - INSERT/UPDATE/DELETE: Perubahan data penting lainnya
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            // === Pelaku Aksi ===
            // NULL jika aksi dilakukan oleh sistem/scheduler
            $table->uuid('user_id')->nullable()->comment('FK ke users yang melakukan aksi (NULL jika sistem)');
            // Snapshot nama & role saat aksi (agar log tetap valid jika user dihapus)
            $table->string('user_name')->nullable()->comment('Snapshot nama pengguna saat aksi dilakukan');
            $table->enum('user_role', [
                'dosen', 'mahasiswa', 'p3m', 'rtpu', 'pimpinan', 'eksternal',
            ])->nullable()->comment('Snapshot role pengguna saat aksi dilakukan');

            // === Detail Aksi ===
            $table->enum('aksi', [
                'INSERT',
                'UPDATE',
                'DELETE',
                'APPROVE',
                'REJECT',
                'REVISI',
                'SET_HARGA',
                'PUBLISH',
                'UNPUBLISH',
                'VERIFIKASI_AKUN',
            ])->comment('Jenis aksi yang dilakukan');

            $table->string('nama_tabel', 100)->comment('Nama tabel yang terpengaruh, e.g. inovasi, transaksi, users');
            $table->uuid('record_id')->nullable()->comment('Primary key record yang diubah');

            // === Data Perubahan (JSON) ===
            // Fleksibel: bisa menyimpan perubahan dari tabel mana pun
            $table->jsonb('nilai_lama')->nullable()->comment('Snapshot data SEBELUM diubah');
            $table->jsonb('nilai_baru')->nullable()->comment('Snapshot data SESUDAH diubah');

            // Deskripsi yang mudah dibaca manusia
            $table->text('deskripsi')->nullable()->comment('e.g. "Menetapkan HPP = 500000, Harga Jual = 750000 untuk inovasi XYZ"');

            // === Metadata Teknis ===
            $table->string('ip_address', 45)->nullable()->comment('IP address pelaku aksi');
            $table->text('user_agent')->nullable()->comment('Browser/client info');

            // Hanya created_at (immutable — tidak ada updated_at)
            $table->timestampTz('created_at')->default(DB::raw('NOW()'));

            // Index untuk query audit yang umum
            $table->index('user_id');
            $table->index('nama_tabel');
            $table->index('record_id');
            $table->index('aksi');
            $table->index(['created_at']);
            $table->index(['nama_tabel', 'record_id']);
        });

        // FK ke users (SET NULL agar log tidak hilang jika user dihapus)
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('audit_logs');
    }
};

