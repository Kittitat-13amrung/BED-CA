<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Channel;
use App\Models\YoutubeVideo;

class Comments extends Model
{
    use HasFactory;

    // allow attributes to be mass assigned
    protected $fillable = [
        'comment',
        'likes',
        'dislikes',
        'commented_at'
    ];

    protected $attributes = [
        'likes' => 0,
        'dislikes' => 0,
    ];

    // comments made by channels
    // return comments that belong the the channel
    public function channels()
    {
        return $this->belongsToMany(Channel::class, 'channel_comment', 'comment_id', 'channel_id')->withTimestamps();
    }

    // comments belong in many videos
    // return the video that comments belonged to
    public function video()
    {
        return $this->belongsTo(YoutubeVideo::class, 'youtube_video_id', 'id');
    }
}
