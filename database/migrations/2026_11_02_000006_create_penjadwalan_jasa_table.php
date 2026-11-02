<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Penjadwalan Jasa
 *
 * Menyimpan jadwal pelaksanaan untuk item bertipe 'jasa'.
 * Dibuat oleh RTPU setelah transaksi berstatus 'lunas'.
 *
 * Relasi ONE-TO-ONE dengan detail_transaksi:
 *   Satu line item jasa = satu jadwal pelaksanaan.
 *
 * Alur status jadwal:
 *   menunggu_jadwal → terjadwal (RTPU isi waktu & lokasi)
 *   terjadwal       → selesai   (RTPU upload bukti pelaksanaan)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjadwalan_jasa', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            // ONE-TO-ONE: satu detail_transaksi jasa = satu jadwal
            // UNIQUE constraint memastikan tidak ada duplikat jadwal per item
            $table->uuid('detail_transaksi_id')->unique()->comment('FK ke detail_transaksi (tipe_item = jasa), one-to-one');

            // === Waktu Pelaksanaan ===
            $table->timestampTz('waktu_mulai')->comment('Tanggal & jam mulai pelaksanaan jasa');
            $table->timestampTz('waktu_selesai')->comment('Tanggal & jam selesai pelaksanaan jasa');

            // Lokasi
            $table->string('lokasi', 255)->nullable()->comment('Nama ruangan/lab, e.g. Lab Uji Material Gedung H Lt.2');
            $table->text('keterangan_jadwal')->nullable()->comment('Instruksi atau informasi tambahan untuk pembeli');

            // === Status Pelaksanaan ===
            $table->enum('status_jadwal', [
                'menunggu_jadwal', // Transaksi lunas, RTPU belum buat jadwal
                'terjadwal',       // Jadwal sudah ditetapkan RTPU
                'selesai',         // Jasa selesai dilaksanakan
            ])->default('menunggu_jadwal');

            // === Bukti Pelaksanaan (diisi setelah jasa selesai) ===
            $table->text('url_bukti_pelaksanaan')->nullable()->comment('URL foto/laporan bukti jasa telah dilaksanakan');
            $table->text('catatan_pelaksanaan')->nullable()->comment('Catatan hasil pelaksanaan dari RTPU/pelaksana');

            // RTPU yang membuat jadwal
            $table->uuid('dijadwalkan_oleh')->nullable()->comment('FK ke users (role: rtpu) yang membuat jadwal');
            $table->timestampTz('dijadwalkan_pada')->nullable();

            $table->timestampsTz();

            // Index
            $table->index('status_jadwal');
            $table->index(['waktu_mulai', 'waktu_selesai']);
        });

        // === Foreign Keys ===
        Schema::table('penjadwalan_jasa', function (Blueprint $table) {
            // RESTRICT: tidak bisa hapus detail_transaksi yang sudah punya jadwal
            $table->foreign('detail_transaksi_id')
                  ->references('id')->on('detail_transaksi')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');

            // RTPU yang membuat jadwal
            $table->foreign('dijadwalkan_oleh')
                  ->references('id')->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('penjadwalan_jasa', function (Blueprint $table) {
            $table->dropForeign(['detail_transaksi_id']);
            $table->dropForeign(['dijadwalkan_oleh']);
        });
        Schema::dropIfExists('penjadwalan_jasa');
    }
};

