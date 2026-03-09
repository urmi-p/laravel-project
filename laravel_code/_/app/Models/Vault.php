<?php

namespace App\Models;

use App\Helper;
use Illuminate\Database\Eloquent\Model;
use App\Services\BunnyStreamService;

class Vault extends Model
{
  protected $fillable = [
    'user_id',
    'type',
    'image',
    'width',
    'height',
    'img_type',
    'video',
    'encoded',
    'video_poster',
    'duration_video',
    'quality_video',
    'video_embed',
    'music',
    'file',
    'bunny_video_id',
    'file_name',
    'file_size',
    'bytes',
    'mime',
    'status',
    'job_id',
    'created_at'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  protected static function boot()
  {
    parent::boot();

    static::deleting(function ($vault) {
      if (!empty($vault->bunny_video_id)) {
        try {
          $bunnyStreamService = app(BunnyStreamService::class);
          if ($bunnyStreamService->isConfigured()) {
            $bunnyStreamService->deleteVideo($vault->bunny_video_id);
          }
        } catch (\Exception $e) {
          \Log::warning('Error deleting Bunny vault video', [
            'vault_id' => $vault->id,
            'video_id' => $vault->bunny_video_id,
            'error' => $e->getMessage(),
          ]);
        }
      }

      MediaMessages::where('vault_id', $vault->id)->delete();
    });
  }

  public function getPreviewAttribute()
  {
    $previewDefault = url('/img/placeholder.png');
    $preview = $this->type == 'image'
      ? $this->file
      : ($this->video_poster ?? null);

    $previewFile = $this->type == 'video'
      ? Helper::vaultThumbnailUrl($this, $previewDefault)
      : ($preview ? Helper::vaultFileUrl($preview) : $previewDefault);

    return $previewFile;
  }
}
