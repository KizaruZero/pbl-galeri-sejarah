<?php

namespace App\Filament\Resources\ContentReactionResource\Pages;

use App\Filament\Resources\ContentReactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContentReaction extends EditRecord
{
    protected static string $resource = ContentReactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
