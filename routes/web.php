<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadController;

Route::get('/', function () {
    return view('portal.index');
});
/*
|--------------------------------------------------------------------------
| Portal Publik — JDIH LLDIKTI Wilayah XI
|--------------------------------------------------------------------------
*/

Route::prefix('portal')->name('portal.')->group(function () {
    // Halaman utama daftar peraturan
    Route::get('/', function () {
        return view('portal.index');
    })->name('index');
    // Halaman detail peraturan
    Route::get('/peraturan/{id}', function (int $id) {
        return view('portal.detail', ['id' => $id]);
    })->name('detail');
    // Serve PDF untuk pratinjau di browser (PDF.js) — TIDAK increment download_count
    Route::get('/peraturan/{id}/pdf', [DownloadController::class, 'view'])
        ->name('pdf.view');

    // Download PDF (force download) — download_count sudah di-increment oleh Livewire
    Route::get('/peraturan/{id}/download', [DownloadController::class, 'unduh'])
        ->name('download');
});
