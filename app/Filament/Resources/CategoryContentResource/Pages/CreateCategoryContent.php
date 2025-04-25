<?php

namespace App\Filament\Resources\CategoryContentResource\Pages;

use App\Filament\Resources\CategoryContentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoryContent extends CreateRecord
{
    protected static string $resource = CategoryContentResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
