<?php

namespace App\Filament\Resources\CategoryContentResource\Pages;

use App\Filament\Resources\CategoryContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoryContent extends EditRecord
{
    protected static string $resource = CategoryContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
