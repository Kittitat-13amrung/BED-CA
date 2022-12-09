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
        // generate a bunch of channel without any relationships
        // or youtube video and comments attached to it
        // Channel::factory()->times(3)->create();


        // generate chanels with videos and comments attached as relationships
        // this populates each channel with 10 videos and in each video
        // also populates it with a load of comments
        // Channel::factory()->count(3)->create()->each(function ($channel) {
        //     YoutubeVideo::factory()->count(10)->create([
        //         'channel_id' => $channel->id // attach the channel id to the video
        //     ])->each(function ($video) {
        //         Comments::factory()->count(10)->create([
        //             'youtube_video_id' => $video->id // attach the video id that comment belongs to
        //         ])->each(function ($comment) {

        //             $rng = fake()->numberBetween($min = 1, $max = 3); //random number between 1 to 3

        //             Channel_Comment::create([
        //                 'channel_id' => $rng, // $rng is used to randomised channel who commented
        //                 'comment_id' => $comment->id
        //             ]);
        //         });
        //     });
        // });

        // Create channels and populate videos with comments
        Channel::factory(30)->hasVideos(10)->hasComments(10)->create();
    }
}
