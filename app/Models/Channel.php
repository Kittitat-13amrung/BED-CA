<?php

namespace App\Models;

use App\Models\YoutubeVideo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Channel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    // allow mass assignment of the attributes specified below
    protected $fillable = [
        'name',
        'email',
        'subscribers',
        'created_at',
        'password'
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

    protected $casts = [
        'email_verified_at' => 'datetime',
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

}
