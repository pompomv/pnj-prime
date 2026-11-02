<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Media Inovasi
 *
 * Menyimpan galeri foto, video, atau dokumen tambahan
 * untuk setiap inovasi (mendukung multiple media per inovasi).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_inovasi', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            // FK ke inovasi (CASCADE: jika inovasi dihapus, medianya ikut terhapus)
            $table->uuid('inovasi_id');

            $table->text('url_media')->comment('URL ke file media di storage (S3/MinIO/local)');
            $table->string('tipe_media', 50)->default('foto')->comment('Tipe media: foto, video, dokumen');
            $table->string('keterangan', 255)->nullable()->comment('Keterangan singkat foto/media');
            $table->smallInteger('urutan')->default(1)->comment('Urutan tampil di galeri, mulai dari 1');

            $table->timestampTz('created_at')->default(DB::raw('NOW()'));

            $table->index('inovasi_id');
        });

        Schema::table('media_inovasi', function (Blueprint $table) {
            $table->foreign('inovasi_id')
                  ->references('id')->on('inovasi')
                  ->onDelete('cascade')  // Hapus inovasi → media ikut terhapus
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('media_inovasi', function (Blueprint $table) {
            $table->dropForeign(['inovasi_id']);
        });
        Schema::dropIfExists('media_inovasi');
    }
};

