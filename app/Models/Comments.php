<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Channel;
use App\Models\YoutubeVideo;

class Comments extends Model
{
    use HasFactory;

    protected $guarded = [];

    // public function channels() {
    //     return $this->belongsToMany(Channel::class);
    // }

    public function channels() {
        return $this->belongsToMany(Channel::class, 'channel_comment', 'comment_id', 'channel_id');
    }

}
