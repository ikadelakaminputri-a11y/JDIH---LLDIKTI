<?php

namespace App\Http\Controllers;

use App\Models\Regulation;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    /**
     * Serve PDF untuk dibaca di browser (PDF.js viewer).
     * Tidak menambah download_count — hanya untuk pratinjau.
     */
    public function view(int $id): StreamedResponse
    {
        $peraturan = Regulation::with('activeVersion')
            ->where('id', $id)
            ->where('status', 'published')
            ->firstOrFail();

        $dokumen = $peraturan->activeVersion;

        abort_if(! $dokumen, 404);
        abort_if(! Storage::disk('public')->exists($dokumen->file_path), 404);

        return Storage::disk('public')->response(
            $dokumen->file_path,
            basename($dokumen->file_path),
            ['Content-Type' => 'application/pdf']
        );
    }

    /**
     * Download PDF (force download).
     * Download count sudah di-increment oleh Livewire method unduh(),
     * sehingga controller ini TIDAK perlu increment lagi.
     */
    public function unduh(int $id): BinaryFileResponse
    {
        $peraturan = Regulation::with('activeVersion')
            ->where('id', $id)
            ->where('status', 'published')
            ->firstOrFail();

        $dokumen = $peraturan->activeVersion;

        abort_if(! $dokumen, 404);
        abort_if(! Storage::disk('public')->exists($dokumen->file_path), 404);

        return Storage::disk('public')->download(
            $dokumen->file_path,
            basename($dokumen->file_path)
        );
    }
}
