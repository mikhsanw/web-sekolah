<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spmb_periodes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tahun_ajaran')->unique();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->dateTime('tanggal_buka')->nullable();
            $table->dateTime('tanggal_tutup')->nullable();
            $table->integer('kuota')->nullable();
            $table->longText('informasi')->nullable();
            $table->longText('syarat')->nullable();
            $table->boolean('dibuka')->default(false);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spmb_periodes');
    }
};
