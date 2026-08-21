<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('kategori')->default('siswa');
            $table->string('nama_peraih')->nullable();
            $table->string('tingkat')->default('sekolah');
            $table->string('peringkat')->nullable();
            $table->string('penyelenggara')->nullable();
            $table->year('tahun')->nullable();
            $table->date('tanggal')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};
