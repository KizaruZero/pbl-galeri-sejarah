<?php

namespace Tests\Feature\Photo;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class BulkUploadTest extends TestCase
{
    use RefreshDatabase;


    #[Test]
    public function user_can_bulk_upload_photos_successfully()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        Category::factory()->create(['id' => 1]);

        $zip = new \ZipArchive();
        $zipFilePath = storage_path('app/test_bulk.zip');
        $tempExtractPath = storage_path('app/test_bulk_content');

        if (!file_exists($tempExtractPath)) {
            mkdir($tempExtractPath, 0777, true);
        }

        $metadata = [
            ['type', 'file_name', 'title', 'source', 'alt_text', 'description', 'note', 'tag', 'categories', 'link_youtube', 'thumbnail_url'],
            ['photo', 'photo1.jpg', 'Judul Foto', 'Sumber', 'Alt Text', 'Deskripsi Foto', 'Catatan', 'tag1,tag2', '1', '', ''],
        ];

        $metadataExport = new class($metadata) implements \Maatwebsite\Excel\Concerns\FromArray {
            public function __construct(private array $data) {}
            public function array(): array { return $this->data; }
        };

        $excelTempFile = $tempExtractPath . '/metadata_template.xlsx';
        \Maatwebsite\Excel\Facades\Excel::store($metadataExport, 'temp_metadata.xlsx', 'local');
        $savedExcelPath = Storage::disk('local')->path('temp_metadata.xlsx');
        copy($savedExcelPath, $excelTempFile);

        $mediaPath = $tempExtractPath . '/media';
        if (!file_exists($mediaPath)) {
            mkdir($mediaPath, 0777, true);
        }

        $image = UploadedFile::fake()->image('photo1.jpg');
        file_put_contents($mediaPath . '/photo1.jpg', file_get_contents($image->getPathname()));

        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tempExtractPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($tempExtractPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
        }

        $response = $this->actingAs($user)->postJson('/api/bulk-upload', [
            'zip_file' => new \Illuminate\Http\UploadedFile(
                $zipFilePath,
                'test_bulk.zip',
                'application/zip',
                null,
                true
            ),
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'items',
                     'temp_files'
                 ]);
    }

    #[Test]
    public function bulk_upload_fails_when_metadata_excel_missing()
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $zip = new \ZipArchive();
        $zipFilePath = storage_path('app/test_bulk_missing_metadata.zip');
        $tempExtractPath = storage_path('app/test_missing_metadata');

        if (!file_exists($tempExtractPath)) {
            mkdir($tempExtractPath, 0777, true);
        }

        $mediaPath = $tempExtractPath . '/media';
        if (!file_exists($mediaPath)) {
            mkdir($mediaPath, 0777, true);
        }

        $image = UploadedFile::fake()->image('photo1.jpg');
        file_put_contents($mediaPath . '/photo1.jpg', file_get_contents($image->getPathname()));

        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tempExtractPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($tempExtractPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
        }

        $response = $this->actingAs($user)->postJson('/api/bulk-upload', [
            'zip_file' => new \Illuminate\Http\UploadedFile(
                $zipFilePath,
                'test_bulk_missing_metadata.zip',
                'application/zip',
                null,
                true
            ),
        ]);

        $response->assertStatus(500)
                 ->assertJsonFragment([
                     'message' => 'Bulk upload failed: metadata_template.xlsx not found in ZIP file'
                 ]);
    }

    #[Test]
    public function bulk_upload_fails_when_media_file_missing()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        Category::factory()->create(['id' => 1]);

        $zip = new \ZipArchive();
        $zipFilePath = storage_path('app/test_bulk_missing_media.zip');
        $tempExtractPath = storage_path('app/test_missing_media');

        if (!file_exists($tempExtractPath)) {
            mkdir($tempExtractPath, 0777, true);
        }

        $metadata = [
            ['type', 'file_name', 'title', 'source', 'alt_text', 'description', 'note', 'tag', 'categories', 'link_youtube', 'thumbnail_url'],
            ['photo', 'nonexistent.jpg', 'Judul Hilang', 'Sumber', 'Alt Text', 'Deskripsi', 'Catatan', 'tag1', '1', '', ''],
        ];

        $metadataExport = new class($metadata) implements \Maatwebsite\Excel\Concerns\FromArray {
            public function __construct(private array $data) {}
            public function array(): array { return $this->data; }
        };

        $excelTempFile = $tempExtractPath . '/metadata_template.xlsx';
        \Maatwebsite\Excel\Facades\Excel::store($metadataExport, 'temp_metadata.xlsx', 'local');
        $savedExcelPath = Storage::disk('local')->path('temp_metadata.xlsx');
        copy($savedExcelPath, $excelTempFile);

        $mediaPath = $tempExtractPath . '/media';
        if (!file_exists($mediaPath)) {
            mkdir($mediaPath, 0777, true);
        }

        // Tambahkan dummy agar folder tidak kosong dalam ZIP
        file_put_contents($mediaPath . '/.keep', '');

        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tempExtractPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($tempExtractPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
        }

        $response = $this->actingAs($user)->postJson('/api/bulk-upload', [
            'zip_file' => new \Illuminate\Http\UploadedFile(
                $zipFilePath,
                'test_bulk_missing_media.zip',
                'application/zip',
                null,
                true
            ),
        ]);

        $response->assertStatus(500)
         ->assertJsonFragment([
             'message' => 'Bulk upload failed: File tidak ditemukan: nonexistent.jpg - pastikan nama file yang ditulis sama dengan nama file yang di upload'
         ]);

    }


}
