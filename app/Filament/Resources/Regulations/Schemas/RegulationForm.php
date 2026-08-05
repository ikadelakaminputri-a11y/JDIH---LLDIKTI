<?php

namespace App\Filament\Resources\Regulations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RegulationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->placeholder('Pilih kategori')
                    ->required(),
                TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->live(onBlur: true)                          // ← trigger saat blur
                    ->afterStateUpdated(function (string $operation, $state, callable $set) {
                        if ($operation === 'create') {
                            $set('slug', Str::slug($state));      // ← auto-fill slug
                        }
                    }),
                TextInput::make('number')
                    ->label('Nomor')
                    ->required(),
                TextInput::make('year')
                    ->label('Tahun')
                    ->required()
                    ->numeric(),
                // DatePicker::make('publish_date') ← hapus
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'published'   => 'Published',
                        'unpublished' => 'Unpublished',
                    ])
                    ->placeholder('Pilih Status')
                    ->default('unpublished')
                    ->required(),
                FileUpload::make('pdf_file')
    ->label('File PDF (Versi 1)')
    ->disk('public')
    ->directory('regulations')
    ->acceptedFileTypes(['application/pdf'])
    ->maxSize(20480) // 20 MB
    ->required()
    ->columnSpanFull()
    ->visibleOn('create'),
            ]);
    }
}
