<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
  protected $fillable = [
    'updates_id',
    'user_id',
    'type',
    'image',
    'width',
    'height',
    'video',
    'bunny_video_id',
    'video_poster',
    'encoded',
    'duration_video',
    'quality_video',
    'video_embed',
    'music',
    'file',
    'file_name',
    'file_size',
    'bytes',
    'mime',
    'img_type',
    'token',
    'status',
    'created_at'
  ];

  public function user()
  {
    return $this->belongsTo(User::class)->first();
  }

  public function updates()
  {
    return $this->belongsTo(Updates::class);
  }
}
