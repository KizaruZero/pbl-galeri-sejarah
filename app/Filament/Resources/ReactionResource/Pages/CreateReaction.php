<?php

namespace App\Filament\Resources\ReactionResource\Pages;

use App\Filament\Resources\ReactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReaction extends CreateRecord
{
    protected static string $resource = ReactionResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
