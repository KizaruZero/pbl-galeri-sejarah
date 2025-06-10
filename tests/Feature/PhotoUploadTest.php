<?php

namespace Tests\Feature\Photo;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PhotoUploadTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_upload_photo_with_valid_data()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $category = Category::factory()->create();

        $file = UploadedFile::fake()->image('photo.jpg', 800, 600)->size(2000); // 2MB

        $response = $this->actingAs($user)->postJson('/api/content-photo', [
            'title' => 'Candi Borobudur',
            'description' => 'Candi terbesar di Indonesia',
            'image' => $file,
            'source' => 'Museum Nasional',
            'alt_text' => 'Candi Borobudur di pagi hari',
            'note' => 'Dibangun abad ke-9',
            'tag' => 'candi,sejarah',
            'category_ids' => [$category->id],
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('content_photo', ['title' => 'Candi Borobudur']);
    }

    #[Test]
    public function upload_fails_without_image_file()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/content-photo', [
            'title' => 'Candi Prambanan',
            'description' => 'Candi Hindu terbesar',
            'category_ids' => [$category->id],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }

    #[Test]
    public function unauthenticated_user_cannot_upload()
    {
        // Buat kategori agar lolos validasi
        Category::factory()->create(['id' => 1]);
    
        $file = UploadedFile::fake()->image('photo.jpg', 800, 600)->size(2000);
    
        $response = $this->postJson('/api/content-photo', [
            'title' => 'unauth test',
            'description' => 'should fail',
            'image' => $file,
            'category_ids' => [1],
        ]);
    
        $response->assertStatus(401);
        $response->assertJson(['message' => 'User not authenticated']);
    }
    #[Test]
public function upload_fails_if_image_too_large()
{
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $file = UploadedFile::fake()->image('large_photo.jpg')->size(11000); // 6MB

    $response = $this->actingAs($user)->postJson('/api/content-photo', [
        'title' => 'Foto Besar',
        'description' => 'Ukuran file terlalu besar',
        'image' => $file,
        'category_ids' => [$category->id],
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('image');
}
#[Test]
public function upload_fails_if_file_is_not_an_image()
{
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $file = UploadedFile::fake()->create('not_image.pdf', 1000, 'application/pdf');

    $response = $this->actingAs($user)->postJson('/api/content-photo', [
        'title' => 'File Salah',
        'description' => 'Bukan file gambar',
        'image' => $file,
        'category_ids' => [$category->id],
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('image');
}
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

    // Buat metadata excel sebagai file fisik
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

    // Gunakan Storage::disk()->path() agar pathnya benar
    $savedExcelPath = \Storage::disk('local')->path('temp_metadata.xlsx');
    copy($savedExcelPath, $excelTempFile);

    // Buat folder media dan simpan gambar
    $mediaPath = $tempExtractPath . '/media';
    if (!file_exists($mediaPath)) {
        mkdir($mediaPath, 0777, true);
    }

    $image = UploadedFile::fake()->image('photo1.jpg');
    file_put_contents($mediaPath . '/photo1.jpg', file_get_contents($image->getPathname()));

    // Buat file ZIP
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

    // Kirim permintaan bulk upload
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


}
