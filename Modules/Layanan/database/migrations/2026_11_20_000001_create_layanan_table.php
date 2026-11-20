<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan', 150);
            $table->enum('kategori_layanan', ['administrasi', 'kependudukan', 'perizinan', 'kesehatan', 'pendidikan']);
            $table->string('perangkat_daerah', 150);
            $table->text('deskripsi');
            $table->unsignedTinyInteger('tingkat_kematangan'); // skala 1-5
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanan');
    }
};
