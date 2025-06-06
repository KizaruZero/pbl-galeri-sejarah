<?php

namespace App\Filament\Resources\ContentPhotoResource\Pages;

use App\Filament\Resources\ContentPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\MetadataPhoto;

class CreateContentPhoto extends CreateRecord
{
    protected static string $resource = ContentPhotoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Store EXIF data temporarily before removing it from the main data
        if (isset($data['exif_data'])) {
            $this->exifData = $data['exif_data'];
        }

        // Remove exif_data from the main model data since it's not a direct field
        unset($data['exif_data']);

        return $data;
    }

    protected function afterCreate(): void
    {
        // Handle category relationships after creation
        ContentPhotoResource::handleCategoryRelationships(
            $this->record,
            $this->data
        );

        $record = $this->record;

        // Check if we have EXIF data from the form
        $exifData = $this->data['exif_data'] ?? null;

        if ($exifData) {
            MetadataPhoto::create([
                'content_photo_id' => $this->record->id,
                'collection_date' => $exifData['collection_date'] ?? null,
                'file_size' => $exifData['file_size'] ?? null,
                'aperture' => $exifData['aperture'] ?? null,
                'location' => $exifData['location'] ?? null,
                'model' => $exifData['model'] ?? null,
                'ISO' => $exifData['ISO'] ?? null,
                'dimensions' => $exifData['dimensions'] ?? null,
            ]);
        }

    }
}