<?php

namespace App\Filament\Resources\MetadataVideoResource\Pages;

use App\Filament\Resources\MetadataVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMetadataVideo extends CreateRecord
{
    protected static string $resource = MetadataVideoResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
