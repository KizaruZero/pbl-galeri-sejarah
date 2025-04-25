<?php

namespace App\Filament\Resources\ContentReactionResource\Pages;

use App\Filament\Resources\ContentReactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContentReaction extends CreateRecord
{
    protected static string $resource = ContentReactionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
