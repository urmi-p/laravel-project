<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaReel extends Model
{
    protected $fillable = [
        'reels_id',
        'name',
        'bunny_video_id',
        'video_poster',
        'duration_video',
        'job_id',
        'status'
    ];
}
