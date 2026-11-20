<?php

use Illuminate\Support\Facades\Route;
use Modules\Pengaduan\Http\Controllers\PengaduanController;

Route::middleware('auth')->group(function () {

    // Admin dan Warga
    Route::middleware('role:admin,warga')->group(function () {

        // Menampilkan halaman pengaduan
        Route::get('pengaduan', [PengaduanController::class, 'index'])
            ->name('pengaduan.index');

        // Mengambil data pengaduan melalui AJAX
        Route::get('pengaduan/data', [PengaduanController::class, 'getData'])
            ->name('pengaduan.data');

        // Menghapus pengaduan (admin: semua, warga: milik sendiri)
        Route::delete('pengaduan/{pengaduan}', [PengaduanController::class, 'destroy'])
            ->name('pengaduan.destroy');

    });

    // Khusus Warga: kirim dan ubah pengaduan sendiri
    Route::middleware('role:warga')->group(function () {

        // Menyimpan pengaduan
        Route::post('pengaduan', [PengaduanController::class, 'store'])
            ->name('pengaduan.store');

        // Mengambil data pengaduan untuk diedit
        Route::get('pengaduan/{pengaduan}/edit', [PengaduanController::class, 'edit'])
            ->name('pengaduan.edit');

        // Memperbarui pengaduan
        Route::put('pengaduan/{pengaduan}', [PengaduanController::class, 'update'])
            ->name('pengaduan.update');

    });

});
