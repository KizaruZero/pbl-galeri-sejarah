<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Make sure we have users and categories
        $userIds = User::pluck('id')->toArray();
        if (empty($userIds)) {
            // Create a default user if none exists
            $user = User::factory()->create();
            $userIds = [$user->id];
        }


        // Create 50 sample articles
        for ($i = 0; $i < 10; $i++) {
            $title = $faker->sentence(6, true);
            $status = $faker->randomElement(['draft', 'published', 'archived']);
            $publishedAt = $status === 1 ? $faker->dateTimeBetween('-1 year', 'now') : null;

            Article::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'content' => $faker->paragraphs(rand(3, 7), true),
                'user_id' => $faker->randomElement($userIds),
                'image_url' => $faker->boolean(100) ? "/js/Assets/default-photo.jpg" : null,
                'status' => $status,
                'published_at' => $publishedAt,
                'total_views' => $status === 'published' ? $faker->numberBetween(0, 10000) : 0,
            ]);
        }
    }
}