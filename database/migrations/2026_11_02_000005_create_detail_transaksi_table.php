<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Detail Transaksi (Order Items)
 *
 * Rincian item (produk/jasa) dalam satu transaksi.
 *
 * PENTING: harga_saat_transaksi adalah SNAPSHOT harga pada saat beli.
 * Ini memastikan histori transaksi tidak berubah jika harga inovasi
 * diperbarui oleh Pimpinan di kemudian hari.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            // Relasi ke header transaksi
            $table->uuid('transaksi_id')->comment('FK ke transaksi');
            // Relasi ke inovasi yang dibeli
            $table->uuid('inovasi_id')->comment('FK ke inovasi');

            // === Snapshot data saat transaksi (immutable) ===
            // Disimpan agar histori tidak berubah jika data inovasi diupdate
            $table->string('judul_saat_transaksi', 500)->comment('Judul inovasi pada saat transaksi, snapshot');
            $table->enum('tipe_item', ['produk_fisik', 'jasa'])->comment('Tipe item snapshot saat transaksi');

            // === Kalkulasi ===
            $table->integer('kuantitas')->default(1)->comment('Jumlah unit yang dibeli');
            $table->decimal('harga_saat_transaksi', 15, 2)->comment('Harga per unit pada saat beli (snapshot, tidak berubah)');
            $table->decimal('subtotal', 15, 2)->comment('kuantitas × harga_saat_transaksi');

            $table->text('catatan')->nullable()->comment('Catatan khusus per item dari pembeli');

            $table->timestampTz('created_at')->default(DB::raw('NOW()'));

            // Index
            $table->index('transaksi_id');
            $table->index('inovasi_id');
        });

        // === Foreign Keys ===
        Schema::table('detail_transaksi', function (Blueprint $table) {
            // CASCADE: jika transaksi dihapus, item-nya ikut terhapus
            $table->foreign('transaksi_id')
                  ->references('id')->on('transaksi')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            // RESTRICT: tidak bisa hapus inovasi yang sudah pernah dibeli
            $table->foreign('inovasi_id')
                  ->references('id')->on('inovasi')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->dropForeign(['transaksi_id']);
            $table->dropForeign(['inovasi_id']);
        });
        Schema::dropIfExists('detail_transaksi');
    }
};

