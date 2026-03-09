<?php

namespace App\Jobs;

use Throwable;
use App\Helper;
use App\Models\Media;
use App\Models\Updates;
use App\Models\AdminSettings;
use App\Models\Notifications;
use App\Services\BunnyStreamService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\MediaModeration;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\NewPostEvent;

class BunnyUploadVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 3600; // 1 hour for large uploads
    public int $videoId;
    public $video;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $videoId)
    {
        $this->videoId = $videoId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(BunnyStreamService $bunnyService)
    {
        $this->video = Media::findOrFail($this->videoId);
        Log::info('BunnyUploadVideo: Starting upload for Media #' . $this->video->id);

        $settings = AdminSettings::first();
        $post = Updates::whereId($this->video->updates_id)->first();

        if (!$post) {
            Log::warning('BunnyUploadVideo: Post not found for Media #' . $this->video->id);
            return;
        }

        if (!$bunnyService->isConfigured()) {
            throw new \Exception('Bunny Stream is not configured.');
        }

        $localFile = public_path('temp/' . $this->video->video);

        if (!file_exists($localFile)) {
            Log::error('BunnyUploadVideo: Local file not found: ' . $localFile);
            throw new \Exception('Local file not found.');
        }

        try {
            // 1. Sync library settings (watermarks, etc.)
            $bunnyService->syncLibrarySettingsFromApp();

            // 2. Get or create "Posts" collection
            $collectionId = $bunnyService->getOrCreateCollection('Posts');

            // 3. Upload to Bunny Stream
            $title = $this->video->file_name ?: ('Post Video #' . $this->video->id);
            $bunnyVideoId = $bunnyService->uploadVideo($localFile, $title, $collectionId);

            Log::info('BunnyUploadVideo: Uploaded to Bunny. ID: ' . $bunnyVideoId);

            // 4. Fetch metadata with retry
            $videoData = $bunnyService->getVideoWithRetry($bunnyVideoId, 6, 1000);
            $bunnyService->ensureMp4FallbackEnabled($videoData);
            // 5. Update Media record
            $durationSeconds = (int) ($videoData['length'] ?? $videoData['Length'] ?? 0);
            $videoWidth = (int) ($videoData['width'] ?? $videoData['Width'] ?? 0);
            
            Media::whereId($this->video->id)->update([
                'bunny_video_id' => $bunnyVideoId,
                'video' => $bunnyVideoId,
                'video_poster' => $bunnyService->getPosterUrl($bunnyVideoId),
                'duration_video' => $durationSeconds > 0 ? Helper::getDurationInMinutes($durationSeconds) : null,
                'quality_video' => $videoWidth > 0 ? Helper::getResolutionVideo($videoWidth) : null,
                'encoded' => 'yes',
                // 'status' => 'active'
            ]);
            
            // 6. Delete local temp file
            if (file_exists($localFile)) {
                unlink($localFile);
            }

            // 7. Check if all videos for this post are encoded
            $remaining = Media::whereUpdatesId($this->video->updates_id)
                ->where('type', 'video')
                ->where('video_embed', '')
                ->where('encoded', 'no')
                ->count();

            if ($remaining === 0) {
                $statusPost = $post->schedule ? 'schedule' : 'active';
                $statusFinalPost = $settings->auto_approve_post == 'on' ? $statusPost : 'pending';

                $post->update([
                    'status' => $statusFinalPost,
                    'date' => $post->editing ? $post->date : now()
                ]);

                // Notify user
                Notifications::send($this->video->user_id, $this->video->user_id, 9, $this->video->updates_id);
                if (!$settings->disable_new_post_notification) {
                    // Send notification via Email
                    event(new NewPostEvent($post));
                }
                Log::info('BunnyUploadVideo: All videos encoded for Post #' . $post->id);
            }

            // Dispatch Media Moderation Videos
            if ($settings->moderation_status) {
                $getVideoEncoded = Media::whereId($this->video->id)
                    ->where('encoded', 'yes')
                    ->first();
                if ($getVideoEncoded) {
                    dispatch(new MediaModeration($getVideoEncoded));
                }else {
                    info('No encoded video found for updates_id: ' . $this->video->updates_id);
                }
            }
        } catch (\Exception $e) {
            Log::error('BunnyUploadVideo: Error - ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(?Throwable $exception): void
    {
        Log::error('BunnyUploadVideo: Job failed - ' . $exception->getMessage());

        $media = Media::find($this->videoId);
        if (!$media) {
            return;
        }

        $post = Updates::find($media->updates_id);
        if ($post) {
            Notifications::send($post->user_id, $post->user_id, 20, $post->id);
        }
    }
}
