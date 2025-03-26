<?php

namespace App\Filament\Resources\UserFavoriteResource\Pages;

use App\Filament\Resources\UserFavoriteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserFavorite extends EditRecord
{
    protected static string $resource = UserFavoriteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
