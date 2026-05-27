<?php

namespace App\Filament\Resources\Regulations\Pages;

use App\Filament\Resources\Regulations\RegulationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditRegulation extends EditRecord
{
    protected static string $resource = RegulationResource::class;

    public function getRecordTitle(): string
    {
        return Str::limit($this->record->title, 18);
    }

    public function getTitle(): string
    {
        $title = strtoupper($this->record->title ?? '');

        return (str($title)->limit(38));
    }


    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus')
                ->modalHeading('Hapus Peraturan')
                ->modalDescription('Apakah anda yakin ingin menghapus peraturan ini?')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->successNotificationTitle('Peraturan berhasil dihapus'),
            ForceDeleteAction::make()
                ->label('Hapus Permanen')
                ->modalHeading('Hapus Permanen')
                ->modalDescription('Data yang dihapus tidak bisa dikembalikan.')
                ->modalSubmitActionLabel('Hapus Sekarang')
                ->successNotificationTitle('Data berhasil dihapus permanen'),
            RestoreAction::make()
                ->label('Pulihkan')
                ->successNotificationTitle('Peraturan berhasil dipulihkan'),
        ];
    }
}
