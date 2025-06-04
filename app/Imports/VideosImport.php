<?php

namespace App\Imports;

use App\Models\ContentVideo;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Auth;

class VideosImport implements ToModel, WithHeadingRow, WithValidation
{
    private $rowCount = 0;
    private $skippedCount = 0;
    private $userId;

    // Inject the user ID through constructor
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }

    public function model(array $row)
    {
        if (empty($row['title'])) {
            $this->skippedCount++;
            return null;
        }

        $this->rowCount++;
        
        // Generate slug from title
        $slug = Str::slug($row['title']);
        
        return new ContentVideo([
            'title' => $row['title'] ?? '',
            'slug' => $slug,
            'tag' => $row['tag'] ?? null,
            'category' => $row['category'] ?? null,
            'description' => $row['description'] ?? null,
            'source' => $row['source'] ?? 'youtube',
            'link_youtube' => $row['link_youtube'] ?? null,
            'video_url' => $row['video_url'] ?? null,
            'thumbnail' => $row['thumbnail'] ?? null,
            'user_id' => $this->userId, // Set the user_id from the constructor
            'status' => 'pending', // Set default status
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'link_youtube' => 'nullable|url',
            'video_url' => 'nullable|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'title.required' => 'Kolom title harus diisi',
        ];
    }
}