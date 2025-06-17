<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContentPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $photos = [
            // Makanan Tradisional
            [
                'title' => 'Timlo Solo',
                'user_id' => 1,
                'image_url' => 'foto_content/Timlo_Solo_Sastro_without_soup.jpg',
                'description' => 'Timlo Solo, hidangan sup tradisional khas Solo.',
                'source' => 'Dokumentasi Kuliner Solo',
                'alt_text' => 'Timlo Solo tanpa kuah',
                'tag' => 'makanan, tradisional, solo, timlo',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
            ],
            [
                'title' => 'Tengkleng Solo',
                'user_id' => 1,
                'image_url' => 'foto_content/Tengkleng_with_rice,_Solo.jpg',
                'description' => 'Tengkleng, hidangan berbahan dasar daging kambing khas Solo.',
                'source' => 'Dokumentasi Kuliner Solo',
                'alt_text' => 'Tengkleng dengan nasi',
                'tag' => 'makanan, tradisional, solo, tengkleng',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
            ],
            [
                'title' => 'Selat Solo',
                'user_id' => 1,
                'image_url' => 'foto_content/Selat_Solo.jpg',
                'description' => 'Selat Solo, hidangan fusion Belanda-Jawa khas Solo.',
                'source' => 'Dokumentasi Kuliner Solo',
                'alt_text' => 'Selat Solo',
                'tag' => 'makanan, tradisional, solo, selat',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
            ],
            [
                'title' => 'Srabi Solo',
                'user_id' => 1,
                'image_url' => 'foto_content/Srabi_Solo.jpg',
                'description' => 'Srabi Solo, jajanan tradisional khas Solo.',
                'source' => 'Dokumentasi Kuliner Solo',
                'alt_text' => 'Srabi Solo',
                'tag' => 'makanan, tradisional, solo, srabi',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
            ],
            [
                'title' => 'Nasi Liwet Solo',
                'user_id' => 1,
                'image_url' => 'foto_content/Nasi_Liwet_Solo.jpg',
                'description' => 'Nasi Liwet, hidangan nasi tradisional khas Solo.',
                'source' => 'Dokumentasi Kuliner Solo',
                'alt_text' => 'Nasi Liwet Solo',
                'tag' => 'makanan, tradisional, solo, nasi liwet',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
            ],

            // Seni Budaya
            [
                'title' => 'Wayang Orang Sriwedari',
                'user_id' => 1,
                'image_url' => 'foto_content/Wayang_Orang_Sriwedari_Solo.jpg',
                'description' => 'Pertunjukan Wayang Orang di Sriwedari, Solo.',
                'source' => 'Dokumentasi Seni Budaya Solo',
                'alt_text' => 'Wayang Orang Sriwedari',
                'tag' => 'seni, budaya, wayang orang, sriwedari',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
            ],
            [
                'title' => 'Gamelan Keraton Surakarta',
                'user_id' => 1,
                'image_url' => 'foto_content/Gamelan_ceremonial_Munggang,_Kraton_Surakarta.jpg',
                'description' => 'Gamelan ceremonial Munggang di Keraton Surakarta.',
                'source' => 'Dokumentasi Keraton Surakarta',
                'alt_text' => 'Gamelan Keraton',
                'tag' => 'seni, budaya, gamelan, keraton',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
            ],
            [
                'title' => 'Karnaval Batik Solo',
                'user_id' => 1,
                'image_url' => 'foto_content/Karnaval_Batik_Solo_2011_Bennylin.jpg',
                'description' => 'Karnaval Batik Solo menampilkan keindahan batik.',
                'source' => 'Dokumentasi Event Solo',
                'alt_text' => 'Karnaval Batik Solo',
                'tag' => 'seni, budaya, batik, karnaval',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
            ],

            // Tempat Bersejarah
            [
                'title' => 'Pura Mangkunegaran',
                'user_id' => 1,
                'image_url' => 'foto_content/pura-mangkunegaraan.jpg',
                'description' => 'Pura Mangkunegaran, istana bersejarah di Solo.',
                'source' => 'Dokumentasi Cagar Budaya Solo',
                'alt_text' => 'Pura Mangkunegaran',
                'tag' => 'tempat bersejarah, istana, mangkunegaran',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
            ],
            [
                'title' => 'Masjid Zayed Solo',
                'user_id' => 1,
                'image_url' => 'foto_content/MasjidZayedSolo.png',
                'description' => 'Masjid Sheikh Zayed Solo, masjid megah dengan arsitektur timur tengah.',
                'source' => 'Dokumentasi Arsitektur Solo',
                'alt_text' => 'Masjid Zayed Solo',
                'tag' => 'tempat bersejarah, masjid, arsitektur',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
            ],
            [
                'title' => 'Balai Kota Solo',
                'user_id' => 1,
                'image_url' => 'foto_content/BalaiKotaSolo.jpg',
                'description' => 'Balai Kota Solo, bangunan bersejarah peninggalan Belanda.',
                'source' => 'Dokumentasi Cagar Budaya Solo',
                'alt_text' => 'Balai Kota Solo',
                'tag' => 'tempat bersejarah, kolonial, pemerintahan',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
            ],

            // Acara dan Festival
            [
                'title' => 'Grebeg Maulud Keraton Surakarta',
                'user_id' => 1,
                'image_url' => 'foto_content/Grebeg_Maulud_of_Keraton_Surakarta.jpg',
                'description' => 'Tradisi Grebeg Maulud di Keraton Surakarta.',
                'source' => 'Dokumentasi Keraton Surakarta',
                'alt_text' => 'Grebeg Maulud',
                'tag' => 'festival, tradisi, keraton, grebeg',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
            ],
            [
                'title' => 'Kirab Kerbau Bule',
                'user_id' => 1,
                'image_url' => 'foto_content/1024px-Kirab_Kerbau_Bule_Pusaka_Keraton_Surakarta.jpg',
                'description' => 'Kirab Kerbau Bule Pusaka Keraton Surakarta.',
                'source' => 'Dokumentasi Keraton Surakarta',
                'alt_text' => 'Kirab Kerbau Bule',
                'tag' => 'festival, tradisi, keraton, kirab',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
            ],
            [
                'title' => 'Sekaten Yogyakarta',
                'user_id' => 1,
                'image_url' => 'foto_content/Sekaten_Yogyakarta_2011_2.jpg',
                'description' => 'Festival Sekaten, perayaan Maulid Nabi Muhammad SAW.',
                'source' => 'Dokumentasi Festival Budaya',
                'alt_text' => 'Festival Sekaten',
                'tag' => 'festival, tradisi, sekaten',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
            ],
        ];

        foreach ($photos as &$photo) {
            $photo['slug'] = Str::slug($photo['title']);
            $photo['created_at'] = now();
            $photo['updated_at'] = now();
        }

        // Insert data ke database
        DB::table('content_photo')->insert($photos);
    }
}
