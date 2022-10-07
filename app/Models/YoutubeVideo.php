<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Channel;
use App\Models\Comments;

class YoutubeVideo extends Model
{
    use HasFactory;

    // allow the data in this model mass assignable
    protected $guarded = [];

    // setting default value for each attribute
    protected $attributes = [
        'views' => 0,
        'likes' => 0,
        'dislikes' => 0,
        'title' => '',
        'description' => '',
        'duration' => 1,
        'thumbnail' => '',
        'uuid' => ''
    ];

    public function channel() {
        return $this->belongsTo(Channel::class);
    }

    public function comments() {
        return $this->hasMany(Comments::class);
    }
}
