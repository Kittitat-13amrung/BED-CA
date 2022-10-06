<?php

namespace Database\Seeders;

use App\Models\YoutubeVideo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class YoutubeVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        YoutubeVideo::factory()->times(3)->create();
    }
}
