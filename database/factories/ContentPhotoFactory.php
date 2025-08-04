<?php

namespace Database\Factories;

use App\Models\ContentPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ContentPhotoFactory extends Factory
{
    protected $model = ContentPhoto::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'user_id' => \App\Models\User::factory()->create()->id, // otomatis membuat user baru
            'description' => $this->faker->paragraph(),
            'source' => $this->faker->company,
            'alt_text' => $this->faker->words(3, true),
            'tag' => 'tag1,tag2',
            'image_url' => 'foto_content/fake.jpg',
            'status' => 'approved',
        ];
    }
}
