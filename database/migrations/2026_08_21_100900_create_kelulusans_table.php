<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelulusans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kelulusan_periode_id')->constrained('kelulusan_periodes')->cascadeOnDelete();
            $table->string('nisn');
            $table->string('nis')->nullable();
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->string('kelas')->nullable();
            $table->string('status')->default('lulus');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_pengambilan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['kelulusan_periode_id', 'nisn']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelulusans');
    }
};
