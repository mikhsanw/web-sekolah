<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spmb_pendaftars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('spmb_periode_id')->constrained('spmb_periodes')->cascadeOnDelete();
            $table->string('no_pendaftaran')->unique();
            $table->string('nisn');
            $table->string('nik')->nullable();
            $table->string('nama');
            $table->string('jenis_kelamin')->nullable();
            $table->string('agama')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir');
            $table->text('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('nama_orang_tua')->nullable();
            $table->string('status')->default('baru');
            $table->text('catatan')->nullable();
            $table->dateTime('terdaftar_pada')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['spmb_periode_id', 'nisn']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spmb_pendaftars');
    }
};
