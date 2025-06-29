<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ContentPhoto;
use App\Models\UserComment;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    protected $middleware = false;

    public function test_user_can_store_photo_comment()
    {
        Notification::fake();
        ContentPhoto::unsetEventDispatcher();

        $photoOwner = User::factory()->create();
        $commenter = User::factory()->create();

        $photo = ContentPhoto::factory()->create([
            'user_id' => $photoOwner->id,
        ]);

        $response = $this
            ->withSession(['_token' => 'test_csrf_token'])
            ->actingAs($commenter)
            ->post("/comment/photo/{$photo->id}", [
                'content' => 'Komentar uji coba',
                'content_photo_id' => $photo->id,
                'user_id' => $commenter->id,
                '_token' => 'test_csrf_token'
            ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['content' => 'Komentar uji coba']);

        $this->assertDatabaseHas('user_comments', [
            'content' => 'Komentar uji coba',
            'content_photo_id' => $photo->id,
            'user_id' => $commenter->id,
        ]);
    }

    public function test_admin_can_approve_comment_via_api()
    {
        Notification::fake();

        $admin = User::whereHas('roles', fn ($q) => $q->where('name', 'super_admin'))->first();
        if (!$admin) {
            $this->markTestSkipped('Admin dengan role super_admin tidak ditemukan.');
        }

        $photo = ContentPhoto::latest()->first();
        if (!$photo) {
            $this->markTestSkipped('ContentPhoto tidak ditemukan, tambahkan data terlebih dahulu.');
        }

        $user = User::where('id', '!=', $admin->id)->first();
        if (!$user) {
            $this->markTestSkipped('User biasa tidak ditemukan.');
        }

        $comment = UserComment::create([
            'content' => 'Komentar tersembunyi',
            'user_id' => $user->id,
            'content_photo_id' => $photo->id,
            'status' => 'hidden',
        ]);

        Gate::define('update', fn ($user, $comment) => true);

        $response = $this
        ->actingAs($admin)
        ->putJson("/api/user-comments/{$comment->id}", [
        'content' => $comment->content,
        'user_id' => $comment->user_id,
        'status' => 'published',
    ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('user_comments', [
            'id' => $comment->id,
            'status' => 'published',
        ]);
    }
}
