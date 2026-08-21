<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('nip')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('jenis')->default('guru');
            $table->string('jabatan')->nullable();
            $table->string('mata_pelajaran')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('status_kepegawaian')->nullable();
            $table->string('email')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('jenis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
