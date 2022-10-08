<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Comments;
use App\Models\Channel_Comment;
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
        Channel::factory()->times(3)->create();
        // Channel::factory()->count(10)->create()->each(function ($channel) {
        //     YoutubeVideo::factory()->count(10)->create([
        //         'channel_id' => $channel->id
        //     ])->each(function ($video) {
        //         Comments::factory()->count(10)->create([
        //             'youtube_video_id' => $video->id
        //         ])->each(function ($comment) {

        //             $rng = fake()->numberBetween($min = 1, $max = 3);

        //             Channel_Comment::create([
        //                 'channel_id' => $rng,
        //                 'comment_id' => $comment->id
        //             ]);
        //         });
        //     });
        // });
    }
}
