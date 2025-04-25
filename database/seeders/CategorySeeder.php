<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Nature'],
            ['category_name' => 'Wildlife'],
            ['category_name' => 'Urban'],
            ['category_name' => 'Travel'],
            ['category_name' => 'Portrait'],
            ['category_name' => 'Short Films'],
            ['category_name' => 'Fashion'],
            ['category_name' => 'Music'],
            ['category_name' => 'Street'],
            ['category_name' => 'Event'],
            ['category_name' => 'Sports'],
            ['category_name' => 'Movies'],
            ['category_name' => 'Food'],
            ['category_name' => 'Cinematic'],
            ['category_name' => 'Wedding'],
            ['category_name' => 'Animation'],
            ['category_name' => 'Astrophotography'],
            ['category_name' => 'Education'],
            ['category_name' => 'Black & White'],
            ['category_name' => 'Drone'],
        ];

        foreach ($categories as $category) {
            // Generate a slug from the category name
            $category['slug'] = Str::slug($category['category_name']);
            Category::create($category);
        }
    }
}
