<?php

namespace Tests\Feature\Comment;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\User;
use App\Models\ContentPhoto;
use App\Models\ContentVideo;
use App\Models\UserComment;


class CommentUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_post_photo_comment()
    {
        $this->withoutExceptionHandling();
    
        $user = User::factory()->create();
        $photo = ContentPhoto::factory()->create(['user_id' => $user->id]);
    
        $payload = [
            'content' => 'Komentar test',
            'content_photo_id' => $photo->id,
            'user_id' => $user->id,
        ];
    
        $response = $this->postJson("/api/comment/photo/{$photo->id}", $payload);
    
        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => 'success',
                'message' => 'Comment submitted for moderation',
                'content' => 'Komentar test',
            ]);
    
        $this->assertDatabaseHas('user_comments', [
            'content' => 'Komentar test',
            'content_photo_id' => $photo->id,
            'status' => 'hidden',
        ]);
    }

    public function test_user_can_get_photo_comments()
{
    $user = User::factory()->create();
    $photo = ContentPhoto::factory()->create();

    $comment = UserComment::create([
        'content' => 'Komentar publish',
        'user_id' => $user->id,
        'content_photo_id' => $photo->id,
        'status' => 'published',
    ]);

    $response = $this->getJson("/api/comment/photo/{$photo->id}?user_id={$user->id}");

    $response->assertStatus(200)
             ->assertJsonFragment(['content' => 'Komentar publish']);
}



public function test_notification_sent_to_photo_owner()
{
    Notification::fake();

    $photoOwner = User::factory()->create();
    $commenter = User::factory()->create(); // harus beda user
    $photo = ContentPhoto::factory()->create(['user_id' => $photoOwner->id]);

    $response = $this->postJson("/api/comment/photo/{$photo->id}", [
        'content' => 'Test notif',
        'content_photo_id' => $photo->id,
        'user_id' => $commenter->id,
    ]);
    
    $response->dump(); 

    Notification::assertSentTo(
        $photoOwner,
        \App\Notifications\PhotoComment::class,
        function ($notification, $channels) {
            dump($notification); // ⬅️ Lihat isi notifikasi untuk debug
            return true;
        }
    );
}


    
}
