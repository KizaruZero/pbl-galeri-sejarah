<?php

namespace App\Filament\Resources\UserReactionResource\Pages;

use App\Filament\Resources\UserReactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserReaction extends CreateRecord
{
    protected static string $resource = UserReactionResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
