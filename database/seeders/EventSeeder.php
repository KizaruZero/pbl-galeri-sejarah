<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;

class EventSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('events')->truncate();

        $events = [];
        $eventTypes = [
            'Tech Conference', 'Art Exhibition', 'Charity Run',
            'Startup Pitch', 'Music Festival', 'Food Fair',
            'Workshop', 'Seminar', 'Product Launch'
        ];

        $imageThemes = [
            'business', 'tech', 'people', 'nature', 'fashion',
            'food', 'sports', 'architecture', 'animals'
        ];

        for ($i = 0; $i < 10; $i++) {
            $title = $faker->randomElement($eventTypes) . ' ' . $faker->year;
            $dateStart = $faker->dateTimeBetween('now', '+1 year');
            $dateEnd = $faker->dateTimeBetween($dateStart, Carbon::parse($dateStart)->addDays(3));
            $theme = $faker->randomElement($imageThemes);

            $events[] = [
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => $faker->paragraph(3),
                'date_start' => Carbon::parse($dateStart)->format('Y-m-d'),
                'date_end' => Carbon::parse($dateEnd)->format('Y-m-d'),
                'instagram_url' => $faker->boolean(70) ? 'https://instagram.com/'.Str::slug($title) : null,
                'youtube_url' => $faker->boolean(50) ? 'https://youtube.com/'.Str::slug($title) : null,
                'website_url' => $faker->boolean(60) ? 'https://'.Str::slug($title).'.com' : null,
                'contact_person' => $faker->name . ' (' . $faker->phoneNumber . ')',
                'location' => $faker->city . ', ' . $faker->streetAddress,
                'google_maps_url' => $faker->boolean(80) ? 'https://goo.gl/maps/'.$faker->bothify('??????##') : null,
                'image_url' => 'https://source.unsplash.com/800x600/?'.$theme.','.Str::slug($title),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('events')->insert($events);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
