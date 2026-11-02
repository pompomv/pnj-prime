<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Transaksi (Orders)
 *
 * Header transaksi pembelian produk/jasa oleh pengguna Eksternal.
 * Satu transaksi bisa berisi banyak item (lihat tabel detail_transaksi).
 * Pembayaran terpusat ke satu rekening BLU kampus.
 *
 * Alur status pembayaran:
 *   menunggu_pembayaran → lunas (jika dikonfirmasi RTPU)
 *   menunggu_pembayaran → gagal (jika expired/gagal)
 *   menunggu_pembayaran → dibatalkan (jika dibatalkan pembeli/admin)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            // Pembeli (harus pengguna dengan role 'eksternal')
            $table->uuid('pembeli_id')->comment('FK ke users (role: eksternal)');

            // Nomor order yang ditampilkan ke pengguna (human-readable)
            $table->string('nomor_order', 50)->unique()->comment('Format: ORD-YYYYMMDD-XXXX');

            // === Kalkulasi Harga ===
            $table->decimal('subtotal', 15, 2)->default(0)->comment('Total sebelum biaya admin');
            $table->decimal('biaya_admin', 15, 2)->default(0)->comment('Biaya administrasi/platform');
            $table->decimal('total_harga', 15, 2)->comment('Total akhir yang harus dibayar (subtotal + biaya_admin)');

            // === Informasi Pembayaran ===
            $table->string('metode_pembayaran', 100)->nullable()->comment('e.g. Transfer Bank BLU, Virtual Account');
            $table->enum('status_pembayaran', [
                'menunggu_pembayaran', // Order dibuat, menunggu bukti bayar
                'lunas',               // Pembayaran dikonfirmasi RTPU
                'gagal',               // Pembayaran gagal / expired
                'dibatalkan',          // Dibatalkan oleh pembeli atau admin
            ])->default('menunggu_pembayaran');

            // Rekening BLU tujuan pembayaran
            $table->uuid('rekening_blu_id')->nullable()->comment('FK ke rekening_blu');

            // Bukti pembayaran & konfirmasi
            $table->text('url_bukti_pembayaran')->nullable()->comment('URL bukti transfer yang diupload pembeli');
            $table->uuid('dikonfirmasi_oleh')->nullable()->comment('FK ke users (role: rtpu/admin) yang mengkonfirmasi');
            $table->timestampTz('dikonfirmasi_pada')->nullable();

            // Catatan
            $table->text('catatan_pembeli')->nullable();
            $table->text('catatan_admin')->nullable();

            // Batas waktu pembayaran (jika lewat → otomatis gagal/dibatalkan)
            $table->timestampTz('batas_waktu_bayar')->nullable()->comment('Deadline pembayaran, setelah ini order expired');

            $table->timestampsTz();

            // Index
            $table->index(['pembeli_id', 'status_pembayaran']);
            $table->index('nomor_order');
            $table->index('status_pembayaran');
        });

        // === Foreign Keys ===
        Schema::table('transaksi', function (Blueprint $table) {
            // Pembeli eksternal
            $table->foreign('pembeli_id')
                  ->references('id')->on('users')
                  ->onDelete('restrict')   // Tidak bisa hapus user yang punya transaksi
                  ->onUpdate('cascade');

            // Rekening BLU tujuan pembayaran
            $table->foreign('rekening_blu_id')
                  ->references('id')->on('rekening_blu')
                  ->onDelete('set null')
                  ->onUpdate('cascade');

            // RTPU yang mengkonfirmasi pembayaran
            $table->foreign('dikonfirmasi_oleh')
                  ->references('id')->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['pembeli_id']);
            $table->dropForeign(['rekening_blu_id']);
            $table->dropForeign(['dikonfirmasi_oleh']);
        });
        Schema::dropIfExists('transaksi');
    }
};

