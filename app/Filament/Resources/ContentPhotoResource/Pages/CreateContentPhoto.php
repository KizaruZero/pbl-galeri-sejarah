<?php

namespace App\Filament\Resources\ContentPhotoResource\Pages;

use App\Filament\Resources\ContentPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContentPhoto extends CreateRecord
{
    protected static string $resource = ContentPhotoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        // Handle category relationships after creation
        ContentPhotoResource::handleCategoryRelationships(
            $this->record,
            $this->data
        );
    }
}


