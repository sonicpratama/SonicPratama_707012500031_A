<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('layanan_id')->constrained('layanan')->cascadeOnDelete();
            $table->text('deskripsi_pengaduan');
            $table->string('file_bukti')->nullable();   // path di storage/app/public/bukti
            $table->string('nama_file')->nullable();    // nama asli file yang diunggah
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
