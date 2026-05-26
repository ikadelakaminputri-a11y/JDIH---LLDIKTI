<?php

namespace App\Filament\Resources\Regulations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RegulationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('title')
                    ->label('Judul')
                    ->required(),
                TextInput::make('number')
                    ->label('Nomor')
                    ->required(),
                TextInput::make('year')
                    ->label('Tahun')
                    ->required()
                    ->numeric(),
                DatePicker::make('publish_date')
                    ->label('Tanggal Terbit')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'published'   => 'Published',
                        'unpublished' => 'Unpublished',
                    ])
                    ->default('unpublished')
                    ->required(),
                FileUpload::make('pdf_file')
                    ->label('File PDF (Versi 1)')
                    ->disk('public')
                    ->directory('regulations')
                    ->acceptedFileTypes(['application/pdf'])
                    ->required()
                    ->columnSpanFull()
                    ->visibleOn('create'),
            ]);
    }
}
