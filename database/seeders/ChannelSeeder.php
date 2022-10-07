<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\YoutubeVideo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Channel::factory()->times(3)->create();
        Channel::factory()->count(3)->create()->each(function ($channel) {
            YoutubeVideo::factory()->count(10)->create([
                'channel_id' => $channel->id
            ]);
        });
    }
}
