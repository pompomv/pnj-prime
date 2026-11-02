<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Rekening BLU
 *
 * Menyimpan data rekening BLU (Badan Layanan Umum) kampus
 * sebagai tujuan terpusat semua pembayaran transaksi PNJ PRIME.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekening_blu', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            $table->string('nama_bank', 100)->comment('Nama bank, e.g. Bank Mandiri, BNI');
            $table->string('nama_rekening')->comment('Nama pemilik rekening');
            $table->string('nomor_rekening', 50)->unique()->comment('Nomor rekening BLU');
            $table->text('keterangan')->nullable()->comment('Keterangan tambahan, e.g. virtual account, kode unik');
            $table->boolean('is_active')->default(true)->comment('Hanya rekening aktif yang ditampilkan ke pembeli');

            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekening_blu');
    }
};

