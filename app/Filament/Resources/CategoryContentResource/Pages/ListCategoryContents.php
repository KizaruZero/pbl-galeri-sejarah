<?php

namespace App\Filament\Resources\CategoryContentResource\Pages;

use App\Filament\Resources\CategoryContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoryContents extends ListRecords
{
    protected static string $resource = CategoryContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
