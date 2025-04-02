<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MertadataVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            // Metadata untuk content_video 1
            [
                'location' => 'Mount Fuji, Japan',
                'file_size' => 104857600, // 100 MB
                'frame_rate' => '30fps',
                'resolution' => '1920x1080',
                'duration' => '00:05:30',
                'format_file' => 'MP4',
                //'tag' => 'mountain, adventure',
                'codec_video_audio' => 'H.264, AAC',
                'collection_date' => '2023-10-01',
                'content_video_id' => 1, // ID dari content_video
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Metadata untuk content_video 2
            [
                'location' => 'New York, USA',
                'file_size' => 52428800, // 50 MB
                'frame_rate' => '24fps',
                'resolution' => '1280x720',
                'duration' => '00:03:45',
                'format_file' => 'AVI',
                //'tag' => 'city, nightlife',
                'codec_video_audio' => 'MPEG-4, MP3',
                'collection_date' => '2023-09-15',
                'content_video_id' => 2, // ID dari content_video
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Metadata untuk content_video 3
            [
                'location' => 'Amazon Rainforest, Brazil',
                'file_size' => 209715200, // 200 MB
                'frame_rate' => '60fps',
                'resolution' => '3840x2160',
                'duration' => '00:10:00',
                'format_file' => 'MP4',
                //'tag' => 'nature, wildlife',
                'codec_video_audio' => 'H.265, AAC',
                'collection_date' => '2023-08-20',
                'content_video_id' => 3, // ID dari content_video
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Metadata untuk content_video 4
            [
                'location' => 'Kyoto, Japan',
                'file_size' => 78643200, // 75 MB
                'frame_rate' => '30fps',
                'resolution' => '1920x1080',
                'duration' => '00:07:15',
                'format_file' => 'MOV',
                //'tag' => 'autumn, culture',
                'codec_video_audio' => 'H.264, AAC',
                'collection_date' => '2023-07-10',
                'content_video_id' => 4, // ID dari content_video
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Metadata untuk content_video 5
            [
                'location' => 'Bali, Indonesia',
                'file_size' => 157286400, // 150 MB
                'frame_rate' => '25fps',
                'resolution' => '2560x1440',
                'duration' => '00:12:30',
                'format_file' => 'MP4',
                //'tag' => 'beach, sunset',
                'codec_video_audio' => 'H.265, AAC',
                'collection_date' => '2023-06-05',
                'content_video_id' => 5, // ID dari content_video
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data ke tabel metadata_video
        DB::table('metadata_video')->insert($data);
    }
}
