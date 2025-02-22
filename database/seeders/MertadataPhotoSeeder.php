<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MertadataPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            // Metadata untuk content_photo 1
            [
                'collection_date' => '2023-10-01',
                'file_size' => 2048000, // 2 MB
                'aperture' => 'f/2.8',
                'tag' => 'nature, sunset',
                'location' => 'Bali, Indonesia',
                'model' => 'Canon EOS 5D Mark IV',
                'ISO' => '100',
                'dimensions' => '1920x1080',
                'content_photo_id' => 1, // ID dari content_photo
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Metadata untuk content_photo 2
            [
                'collection_date' => '2023-09-15',
                'file_size' => 4096000, // 4 MB
                'aperture' => 'f/4',
                'tag' => 'mountain, hike',
                'location' => 'Mount Fuji, Japan',
                'model' => 'Nikon D850',
                'ISO' => '200',
                'dimensions' => '3840x2160',
                'content_photo_id' => 2, // ID dari content_photo
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Metadata untuk content_photo 3
            [
                'collection_date' => '2023-08-20',
                'file_size' => 1024000, // 1 MB
                'aperture' => 'f/5.6',
                'tag' => 'city, night',
                'location' => 'New York, USA',
                'model' => 'Sony A7 III',
                'ISO' => '400',
                'dimensions' => '1280x720',
                'content_photo_id' => 3, // ID dari content_photo
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Metadata untuk content_photo 4
            [
                'collection_date' => '2023-07-10',
                'file_size' => 3072000, // 3 MB
                'aperture' => 'f/3.5',
                'tag' => 'forest, trail',
                'location' => 'Amazon Rainforest, Brazil',
                'model' => 'Fujifilm X-T4',
                'ISO' => '160',
                'dimensions' => '2560x1440',
                'content_photo_id' => 4, // ID dari content_photo
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Metadata untuk content_photo 5
            [
                'collection_date' => '2023-06-05',
                'file_size' => 5120000, // 5 MB
                'aperture' => 'f/2.0',
                'tag' => 'autumn, leaves',
                'location' => 'Kyoto, Japan',
                'model' => 'Olympus OM-D E-M1 Mark III',
                'ISO' => '320',
                'dimensions' => '1920x1080',
                'content_photo_id' => 5, // ID dari content_photo
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data ke tabel metadata_photo
        DB::table('metadata_photo')->insert($data);
    }
}
