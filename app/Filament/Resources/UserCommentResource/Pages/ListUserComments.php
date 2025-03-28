<?php

namespace App\Filament\Resources\UserCommentResource\Pages;

use App\Filament\Resources\UserCommentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;


class ListUserComments extends ListRecords
{
    protected static string $resource = UserCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return UserCommentResource::getWidgets();
    }


    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'published' => Tab::make()->query(fn($query) => $query->where('status', 'published')),
            'hidden' => Tab::make()->query(fn($query) => $query->where('status', 'hidden')),
        ];
    }
}
