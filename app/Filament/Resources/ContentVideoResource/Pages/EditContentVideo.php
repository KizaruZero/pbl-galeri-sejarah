<?php

namespace App\Filament\Resources\ContentVideoResource\Pages;

use App\Filament\Resources\ContentVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\MetadataVideo;
use Plutuss\Facades\MediaAnalyzer;
use Illuminate\Support\Facades\Storage;
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

        $record = $this->record;

        // Process video metadata if video file was uploaded or changed
        if ($record->wasChanged('video_url') && $record->video_url) {
            // Delete existing metadata if updating
            MetadataVideo::where('content_video_id', $record->id)->delete();

            // Create new metadata
            $this->extractAndSaveVideoMetadata($record);
        }
    }

    private function extractAndSaveVideoMetadata($contentVideo)
    {
        try {
            // Get the correct file path from storage
            $videoPath = $contentVideo->video_url;

            // Check if file exists in storage
            if (!Storage::disk('public')->exists($videoPath)) {
                \Log::error("Video file not found in storage: " . $videoPath);
                return;
            }

            // Get the full absolute path
            $fullPath = Storage::disk('public')->path($videoPath);

            \Log::info("Processing video metadata for file: " . $fullPath);

            // Use MediaAnalyzer with the correct file path
            $videoFile = new \Illuminate\Http\UploadedFile(
                $fullPath,
                basename($fullPath),
                mime_content_type($fullPath),
                null,
                true
            );
            $media = MediaAnalyzer::uploadFile($videoFile);


            // Get file size from storage
            $fileSize = Storage::disk('public')->size($videoPath);

            // Extract codec information
            $codec = null;
            if ($media->getNestedValue('video.codec')) {
                $codec = 'Video: ' . $media->getNestedValue('video.codec');

                // Add audio codec if available
                if ($media->getNestedValue('audio.codec')) {
                    $codec .= ', Audio: ' . $media->getNestedValue('audio.codec');
                }
            }

            // Extract resolution
            $resolution = null;
            if ($media->getNestedValue('video.resolution_x') && $media->getNestedValue('video.resolution_y')) {
                $resolution = $media->getNestedValue('video.resolution_x') . 'x' . $media->getNestedValue('video.resolution_y');
            } elseif ($media->getResolution()) {
                $resolution = $media->getResolution();
            }

            // Extract frame rate (ensure it's a numeric value before rounding)
            $frameRate = $media->getNestedValue('video.frame_rate');
            if ($frameRate && is_numeric($frameRate)) {
                $frameRate = round($frameRate, 2);
            }

            // Extract duration (ensure it's a numeric value before rounding)
            $duration = null;
            if (isset($media->playtime_seconds) && is_numeric($media->playtime_seconds)) {
                $duration = round($media->playtime_seconds, 2);
            }

            // Create metadata record
            MetadataVideo::create([
                'content_video_id' => $contentVideo->id,
                'file_size' => $fileSize,
                'format_file' => $media->getFileFormat(),
                'duration' => $duration,
                'codec_video_audio' => $codec,
                'resolution' => $resolution,
                'frame_rate' => $frameRate,
                'location' => null, // Location would need additional processing if available in metadata
                'collection_date' => now(),
            ]);

            \Log::info("Video metadata extracted successfully for video ID: " . $contentVideo->id);

        } catch (\Exception $e) {
            // Log error but don't stop the upload process
            \Log::error('Failed to extract video metadata for video ID ' . $contentVideo->id . ': ' . $e->getMessage());
        }
    }
}
