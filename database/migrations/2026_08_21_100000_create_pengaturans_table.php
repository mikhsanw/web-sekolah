<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kelompok')->default('umum');
            $table->string('kunci')->unique();
            $table->string('label');
            $table->text('nilai')->nullable();
            $table->string('tipe')->default('text');
            $table->string('keterangan')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};
