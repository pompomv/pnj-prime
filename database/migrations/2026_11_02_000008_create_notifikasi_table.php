<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Notifikasi
 *
 * Notifikasi in-app untuk semua pengguna sistem.
 * Digunakan untuk memberi tahu:
 *   - Pengaju (Dosen/Mahasiswa): status kurasi inovasi mereka
 *   - Pembeli (Eksternal): status pembayaran dan jadwal jasa
 *   - RTPU: ada inovasi baru untuk dipublikasi, ada pembayaran baru, dll.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            // Penerima notifikasi
            $table->uuid('user_id')->comment('FK ke users penerima notifikasi');

            $table->string('judul')->comment('Judul singkat notifikasi');
            $table->text('pesan')->comment('Isi pesan notifikasi lengkap');

            // Tipe notifikasi untuk styling UI
            $table->string('tipe', 50)->default('info')
                  ->comment('Tipe untuk warna/ikon UI: info, sukses, peringatan, error');

            // Referensi opsional ke entitas terkait (untuk deep-link)
            $table->string('ref_tabel', 100)->nullable()->comment('Nama tabel entitas terkait, e.g. inovasi, transaksi, penjadwalan_jasa');
            $table->uuid('ref_id')->nullable()->comment('ID entitas terkait untuk deep-link ke halaman detail');

            // Status baca
            $table->boolean('is_read')->default(false);
            $table->timestampTz('dibaca_pada')->nullable();

            $table->timestampTz('created_at')->default(DB::raw('NOW()'));

            // Index: query notifikasi yang belum dibaca per user
            $table->index('user_id');
            $table->index(['user_id', 'is_read']);
        });

        // FK ke users (CASCADE: notifikasi ikut terhapus jika user dihapus)
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('notifikasi');
    }
};

