<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaWelcomeMessage extends Model
{
  protected $fillable = [
    'creator_id',
    'type',
    'file',
    'bunny_video_id',
    'width',
    'height',
    'video_poster',
    'duration_video',
    'quality_video',
    'encoded',
    'file_name',
    'file_size',
    'file_size_bytes',
    'mime_type',
    'token',
    'status',
    'created_at'
  ];

  public function creator()
  {
    return $this->belongsTo(User::class);
  }
}
