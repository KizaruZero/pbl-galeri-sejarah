<?php

namespace App\Filament\Resources\ContentVideoResource\Pages;

use App\Filament\Resources\ContentVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContentVideo extends CreateRecord
{
    protected static string $resource = ContentVideoResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
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
