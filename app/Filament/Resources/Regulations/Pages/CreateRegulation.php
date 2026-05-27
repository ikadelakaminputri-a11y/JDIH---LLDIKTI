<?php

namespace App\Filament\Resources\Regulations\Pages;

use App\Filament\Resources\Regulations\RegulationResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateRegulation extends CreateRecord
{
    protected static string $resource = RegulationResource::class;
    protected ?string $pdfFilePath = null;

    public function getBreadcrumb(): string
    {
        return 'Halaman Tambah Peraturan';
    }

    public function getTitle(): string
    {
        return 'Tambah Peraturan';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->pdfFilePath = $data['pdf_file'] ?? null;

        unset($data['pdf_file']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if (empty($this->pdfFilePath)) {
            return;
        }

        $this->record->addVersion([
            'file_path'   => $this->pdfFilePath,
            'file_size'   => Storage::disk('public')->size($this->pdfFilePath),
            'uploaded_by' => Auth::id(),
            'notes'       => null,
        ]);
    }
}
