<?php

namespace App\Filament\Resources\UserReactionResource\Pages;

use App\Filament\Resources\UserReactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserReaction extends EditRecord
{
    protected static string $resource = UserReactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
