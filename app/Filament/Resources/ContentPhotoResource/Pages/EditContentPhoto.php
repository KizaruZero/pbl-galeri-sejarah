<?php

namespace App\Filament\Resources\ContentPhotoResource\Pages;

use App\Filament\Resources\ContentPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContentPhoto extends EditRecord
{
    protected static string $resource = ContentPhotoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Handle category relationships after save
        ContentPhotoResource::handleCategoryRelationships(
            $this->record,
            $this->data
        );
    }
}
