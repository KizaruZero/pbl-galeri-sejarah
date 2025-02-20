<?php

namespace App\Filament\Resources\MetadataVideoResource\Pages;

use App\Filament\Resources\MetadataVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMetadataVideo extends EditRecord
{
    protected static string $resource = MetadataVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
