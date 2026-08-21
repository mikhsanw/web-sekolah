<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelulusan_periodes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tahun_ajaran')->unique();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->dateTime('tanggal_pengumuman')->nullable();
            $table->date('pengambilan_mulai')->nullable();
            $table->date('pengambilan_selesai')->nullable();
            $table->string('tempat_pengambilan')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('dibuka')->default(false);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelulusan_periodes');
    }
};
