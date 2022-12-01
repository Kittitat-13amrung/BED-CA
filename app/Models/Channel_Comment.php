<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel_Comment extends Model
{

    use HasFactory;

    // manually set the table name
    protected $table = 'channel_comment';

    // allow attributes to be mass assigned
    protected $fillable = [
        'comment_id',
        'channel_id'
    ];
}
