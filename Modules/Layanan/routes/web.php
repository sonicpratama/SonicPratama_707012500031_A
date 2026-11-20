<?php

use Illuminate\Support\Facades\Route;
use Modules\Layanan\Http\Controllers\LayananController;

Route::middleware('auth')->group(function () {

    // Admin dan Warga: melihat daftar layanan
    Route::middleware('role:admin,warga')->group(function () {

        Route::get('layanan', [LayananController::class, 'index'])
            ->name('layanan.index');

    });

    // Khusus Admin: tambah, ubah, dan hapus layanan
    Route::middleware('role:admin')->group(function () {

        Route::get('layanan/create', [LayananController::class, 'create'])
            ->name('layanan.create');

        Route::post('layanan', [LayananController::class, 'store'])
            ->name('layanan.store');

        Route::get('layanan/{layanan}/edit', [LayananController::class, 'edit'])
            ->name('layanan.edit');

        Route::put('layanan/{layanan}', [LayananController::class, 'update'])
            ->name('layanan.update');

        Route::delete('layanan/{layanan}', [LayananController::class, 'destroy'])
            ->name('layanan.destroy');

    });

});
