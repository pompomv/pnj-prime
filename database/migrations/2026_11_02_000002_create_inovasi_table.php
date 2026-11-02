<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Inovasi (Produk & Jasa)
 *
 * Menyimpan semua inovasi yang diajukan oleh Dosen / Mahasiswa.
 * Melewati alur 3 tahap:
 *   1. Kurasi P3M  → status_kurasi: menunggu_validasi → revisi/ditolak/lolos_p3m
 *   2. Penetapan Harga Pimpinan → hpp & harga_jual diisi
 *   3. Publikasi RTPU → is_published = true, deskripsi_promosi diisi
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inovasi', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            // Pengaju inovasi (Dosen atau Mahasiswa)
            $table->uuid('pengaju_id')->comment('FK ke users (role: dosen/mahasiswa)');

            // === Informasi Inovasi ===
            $table->string('judul', 500);
            $table->string('slug', 550)->nullable()->unique()->comment('URL-friendly version dari judul untuk routing');
            $table->text('deskripsi');

            // Tipe item: produk fisik atau jasa
            $table->enum('tipe_item', ['produk_fisik', 'jasa'])->comment('Jenis inovasi yang diajukan');

            // Dokumen & Media
            $table->text('url_dokumen_pendukung')->nullable()->comment('URL ke file proposal/laporan (PDF)');
            $table->text('url_foto_prototipe')->nullable()->comment('URL ke foto utama prototipe');

            // === TAHAP 1: Kurasi P3M ===
            $table->enum('status_kurasi', [
                'menunggu_validasi', // Baru diajukan
                'revisi',            // P3M minta revisi
                'ditolak',           // Ditolak P3M/RTPU
                'lolos_p3m',         // Disetujui, siap penetapan harga
            ])->default('menunggu_validasi')->comment('Status alur kurasi inovasi');

            $table->uuid('dikurasi_oleh')->nullable()->comment('FK ke users (role: p3m) yang melakukan kurasi');
            $table->timestampTz('dikurasi_pada')->nullable();
            $table->text('catatan_revisi')->nullable()->comment('Instruksi revisi dari P3M atau catatan penolakan');

            // === TAHAP 2: Penetapan Harga oleh Pimpinan ===
            // Diisi setelah status_kurasi = 'lolos_p3m'
            $table->decimal('hpp', 15, 2)->nullable()->comment('Harga Pokok Penjualan, ditetapkan Pimpinan');
            $table->decimal('harga_jual', 15, 2)->nullable()->comment('Harga Pasar / Harga Jual ke konsumen, ditetapkan Pimpinan');
            $table->uuid('harga_ditetapkan_oleh')->nullable()->comment('FK ke users (role: pimpinan) yang menetapkan harga');
            $table->timestampTz('harga_ditetapkan_pada')->nullable();

            // === TAHAP 3: Publikasi oleh RTPU ===
            // Diisi setelah harga ditetapkan
            $table->boolean('is_published')->default(false)->comment('false = draft/tidak tampil di marketplace publik');
            $table->text('deskripsi_promosi')->nullable()->comment('Deskripsi marketing yang ditulis RTPU untuk marketplace');
            $table->uuid('dipublikasi_oleh')->nullable()->comment('FK ke users (role: rtpu) yang mempublikasikan');
            $table->timestampTz('dipublikasi_pada')->nullable();

            // === Stok & Satuan ===
            // stok hanya relevan untuk produk_fisik; NULL untuk jasa
            $table->integer('stok')->nullable()->default(0)->comment('Stok tersedia (hanya untuk produk_fisik)');
            $table->string('satuan', 50)->nullable()->default('pcs')->comment('Satuan item, e.g. pcs, unit, sesi, jam');

            $table->timestampsTz();

            // Index untuk query yang sering dilakukan
            $table->index('pengaju_id');
            $table->index('status_kurasi');
            $table->index('tipe_item');
            $table->index('is_published');
            $table->index(['is_published', 'tipe_item']);
        });

        // === Foreign Keys ===
        Schema::table('inovasi', function (Blueprint $table) {
            // Pengaju: Dosen / Mahasiswa
            $table->foreign('pengaju_id')
                  ->references('id')->on('users')
                  ->onDelete('restrict')   // Tidak bisa hapus user jika punya inovasi
                  ->onUpdate('cascade');

            // P3M yang mengkurasi
            $table->foreign('dikurasi_oleh')
                  ->references('id')->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');

            // Pimpinan yang menetapkan harga
            $table->foreign('harga_ditetapkan_oleh')
                  ->references('id')->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');

            // RTPU yang mempublikasikan
            $table->foreign('dipublikasi_oleh')
                  ->references('id')->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('inovasi', function (Blueprint $table) {
            $table->dropForeign(['pengaju_id']);
            $table->dropForeign(['dikurasi_oleh']);
            $table->dropForeign(['harga_ditetapkan_oleh']);
            $table->dropForeign(['dipublikasi_oleh']);
        });
        Schema::dropIfExists('inovasi');
    }
};

