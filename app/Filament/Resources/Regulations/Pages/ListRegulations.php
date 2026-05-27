<?php

namespace App\Filament\Resources\Regulations\Pages;

use App\Filament\Resources\Regulations\RegulationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegulations extends ListRecords
{
    protected static string $resource = RegulationResource::class;

    public function getBreadcrumb(): string
    {
        return 'Halaman Peraturan';
    }

    public function getTitle(): string
    {
        return 'Daftar Peraturan';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Buat Peraturan'),
        ];
    }
}
