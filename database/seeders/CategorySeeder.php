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
            [
                'category_name' => 'Makanan Tradisional',
                'category_image' => 'category/Makanan.jpg'
            ],
            [
                'category_name' => 'Seni Budaya',
                'category_image' => 'category/Seni.jpg'
            ],
            [
                'category_name' => 'Tempat Bersejarah',
                'category_image' => 'category/Tempat.jpg'
            ],
            [
                'category_name' => 'Alam dan Ruang Terbuka',
                'category_image' => 'category/Terbuka.png'
            ],
            [
                'category_name' => 'Acara dan Festival',
                'category_image' => 'category/Festival.jpg'
            ],
        ];

        foreach ($categories as $category) {
            // Generate a slug from the category name
            $category['slug'] = Str::slug($category['category_name']);
            Category::create($category);
        }
    }
}
