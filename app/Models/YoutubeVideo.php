<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Channel;
use App\Models\Comments;

class YoutubeVideo extends Model
{
    use HasFactory;

    // allow the data in this model mass assignable on specified attributes below
    protected $fillable = [
        'title',
        'description',
        'likes',
        'dislikes',
        'duration',
        'views',
        'thumbnail',
        'created_at'
    ];

    // setting default value for each attribute
    protected $attributes = [
        'views' => 0,
        'likes' => 0,
        'dislikes' => 0,
        'title' => '',
        'description' => '',
        'duration' => 1,
        'thumbnail' => '',
        'uuid' => '',
        'channel_id' => 2,
    ];

    // videos belongs to channel
    // return channel of the video
    public function channel() {
        return $this->belongsTo(Channel::class);
    }

    // videos has many comments
    // return comments in the video
    public function comments() {
        return $this->hasMany(Comments::class);
    }
}
