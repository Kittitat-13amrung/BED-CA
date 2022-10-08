<?php

namespace App\Models;

use App\Models\YoutubeVideo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function videos() {
        return $this->hasMany(YoutubeVideo::class);
    }

    public function comments() {
        return $this->belongsToMany(Comments::class, 'channel_comment');
    }
}
