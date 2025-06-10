<?php

namespace Tests\Feature\Video;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class VideoUploadTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_upload_video_with_valid_data()
    {
        config(['filesystems.default' => 'public']);
        Storage::fake('public');


        $user = User::factory()->create();
        $category = Category::factory()->create();

        $video = UploadedFile::fake()->create('video.mp4', 5000, 'video/mp4'); // 5MB
        $thumbnail = UploadedFile::fake()->image('thumb.jpg');

        $response = $this->actingAs($user)->postJson('/api/content-video', [
            'title' => 'Sejarah Kemerdekaan',
            'description' => 'Penjelasan sejarah kemerdekaan Indonesia',
            'video_url' => $video, 
            'thumbnail' => $thumbnail,
            'source' => 'Arsip Nasional',
            'tag' => 'sejarah,kemerdekaan',
            'link_youtube' => null,
            'category_ids' => [$category->id],
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['title' => 'Sejarah Kemerdekaan']);
        $this->assertDatabaseHas('content_video', ['title' => 'Sejarah Kemerdekaan']);
    }

    #[Test]
    public function upload_video_fails_without_required_fields()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/content-video', [
            // intentionally missing 'title', 'description', etc.
            'category_ids' => [$category->id],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'description']);
    }

    #[Test]
    public function unauthenticated_user_cannot_upload_video()
    {
        Category::factory()->create(['id' => 1]);

        $video = UploadedFile::fake()->create('video.mp4', 5000, 'video/mp4');

        $response = $this->postJson('/api/content-video', [
            'title' => 'Tanpa Login',
            'description' => 'Harus gagal',
            'video_url' => $video,
            'category_ids' => [1],
            'source' => 'Arsip Nasional',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'User not authenticated']);
    }

    #[Test]
public function user_can_upload_video_with_youtube_link_only()
{
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $thumbnail = UploadedFile::fake()->image('thumb.jpg');

    $response = $this->actingAs($user)->postJson('/api/content-video', [
        'title' => 'Dokumenter Sejarah',
        'description' => 'Video dokumenter dari YouTube',
        'link_youtube' => 'https://www.youtube.com/watch?v=abc123xyz',
        'category_ids' => [$category->id],
        'thumbnail' => $thumbnail,
        'source' => 'Arsip Nasional',
        'tag' => 'sejarah,kemerdekaan',


    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('content_video', [
        'title' => 'Dokumenter Sejarah',
        'link_youtube' => 'https://www.youtube.com/watch?v=abc123xyz',
    ]);
}

#[Test]
public function upload_video_fails_if_file_too_large()
{
    config(['filesystems.default' => 'public']);
    Storage::fake('public');

    $user = User::factory()->create();
    $category = Category::factory()->create();
    $thumbnail = UploadedFile::fake()->image('thumb.jpg');

    $largeVideo = UploadedFile::fake()->create('large_video.mp4', 51200, 'video/mp4'); // 50MB

    $response = $this->actingAs($user)->postJson('/api/content-video', [
        'title' => 'Video Besar',
        'description' => 'File terlalu besar',
        'video_url' => $largeVideo,
        'category_ids' => [$category->id],
        'thumbnail' => $thumbnail,
        'source' => 'Arsip Nasional',
        'tag' => 'sejarah,kemerdekaan',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['video_url']);
}


}
