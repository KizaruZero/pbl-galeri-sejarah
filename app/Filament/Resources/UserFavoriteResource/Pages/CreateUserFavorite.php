<?php

namespace App\Filament\Resources\UserFavoriteResource\Pages;

use App\Filament\Resources\UserFavoriteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserFavorite extends CreateRecord
{
    protected static string $resource = UserFavoriteResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
