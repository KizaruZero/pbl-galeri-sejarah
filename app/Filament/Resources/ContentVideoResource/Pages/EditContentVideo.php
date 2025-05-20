<?php

namespace App\Filament\Resources\ContentVideoResource\Pages;

use App\Filament\Resources\ContentVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContentVideo extends EditRecord
{
    protected static string $resource = ContentVideoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ambil ID kategori yang terhubung dengan content video ini
        $categoryIds = $this->record->categoryContents()
            ->pluck('category_id')
            ->toArray();

        // Isi field categories dengan ID kategori yang sudah ada
        $data['categories'] = $categoryIds;

        return $data;
    }

    protected function afterSave(): void
    {
        // Handle category relationships after save
        ContentVideoResource::handleCategoryRelationships(
            $this->record,
            $this->data
        );
    }
}
