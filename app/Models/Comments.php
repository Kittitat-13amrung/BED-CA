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
        'text',
        'likes',
        'dislikes',
        'commented_at'
    ];

    // comments made by channels
    // return comments that belong the the channel
    public function channels() {
        return $this->belongsToMany(Channel::class, 'channel_comment', 'comment_id', 'channel_id');
    }

    // comments belong in many videos
    // return the video that comments belong to
    public function video() {
        return $this->belongsTo(YoutubeVideo::class);
    }

}
