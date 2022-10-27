<?php

namespace App\Models;

use App\Models\YoutubeVideo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    // allow mass assignment of the attributes specified below
    protected $fillable = [
        'name',
        'subscribers',
        'created_at'
    ];

        /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // set default values for following attributes
    protected $attributes = [
        'subscribers' => 0,
    ];

    // connects a One to Many relationship (a channel has many videos) 
    public function videos() {
        return $this->hasMany(YoutubeVideo::class);
    }

    // connects a Many to Many relationship (channels have comments)
    public function comments() {
        return $this->belongsToMany(Comments::class, 'channel_comment', 'channel_id', 'comment_id');
    }

    /**
         * Get the user that owns the Channel
         *
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
    public function comment() {
        return $this->belongsTo(Comment::class, 'channel_id');
    }
}
