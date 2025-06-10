<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->words(2, true);
        return [
            'category_name' => $name,
            'category_description' => $this->faker->sentence(),
            'category_image' => 'default.jpg',
            'slug' => Str::slug($name),
        ];
    }
}
