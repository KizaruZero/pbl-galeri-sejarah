<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserComment;
use App\Models\ContentPhoto;
use App\Models\ContentVideo;
use Illuminate\Database\Eloquent\Factories\Factory;


class UserCommentFactory extends Factory
{
    protected $model = UserComment::class;

    public function definition(): array
    {
        return [
            'content' => $this->faker->sentence,
            'user_id' => User::factory(), // otomatis buat user
            'content_photo_id' => ContentPhoto::factory(), // atau bisa content_video_id
            'content_video_id' => null,
            'status' => 'hidden',
        ];
    }
}
