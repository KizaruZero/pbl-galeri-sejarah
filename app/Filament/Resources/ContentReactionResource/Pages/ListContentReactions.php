<?php

namespace App\Filament\Resources\ContentReactionResource\Pages;

use App\Filament\Resources\ContentReactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContentReactions extends ListRecords
{
    protected static string $resource = ContentReactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
