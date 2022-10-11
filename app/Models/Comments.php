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
    protected $guarded = [];

    // comments made by channels
    public function channels() {
        return $this->belongsToMany(Channel::class, 'channel_comment', 'comment_id', 'channel_id');
    }

    // comments belong in many videos
    public function video() {
        return $this->belongsTo(YoutubeVideo::class);
    }

}
