<?php

namespace App\Jobs;

use FFMpeg\FFProbe;
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
            $syncResult = $bunnyService->syncLibrarySettingsFromApp();
            Log::info('BunnyUploadVideo: Library sync result', [
                'media_id' => $this->video->id,
                'post_id' => $post->id,
                'sync_result' => $syncResult,
            ]);

            // 2. Get or create "Posts" collection
            $collectionId = $bunnyService->getOrCreateCollection('Posts');

            // 3. Upload to Bunny Stream
            $title = $this->video->file_name ?: ('Post Video #' . $this->video->id);
            $bunnyVideoId = $bunnyService->uploadVideo($localFile, $title, $collectionId);

            Log::info('BunnyUploadVideo: Uploaded to Bunny. ID: ' . $bunnyVideoId);

            // Persist the Bunny GUID immediately so it is not lost if a later
            // metadata/transcoding step fails or the worker stops mid-job.
            Media::whereId($this->video->id)->update([
                'bunny_video_id' => $bunnyVideoId,
                'video' => $bunnyVideoId,
                'video_poster' => $bunnyService->getPosterUrl($bunnyVideoId),
            ]);

            Log::info('BunnyUploadVideo: Bunny ID saved to media record', [
                'media_id' => $this->video->id,
                'post_id' => $post->id,
                'bunny_video_id' => $bunnyVideoId,
            ]);

            // 4. Fetch metadata with retry
            $videoData = $bunnyService->getVideoWithRetry($bunnyVideoId, 6, 1000);
            $status = (int) ($videoData['status'] ?? $videoData['Status'] ?? 0);

            // Wait until Bunny finishes processing so watermark/transcodes are applied.
            if ($status !== 4) {
                for ($i = 0; $i < 12; $i++) {
                    if ($status === 4 || $status === 5) {
                        break;
                    }
                    sleep(5);
                    $videoData = $bunnyService->getVideoWithRetry($bunnyVideoId, 3, 1000);
                    $status = (int) ($videoData['status'] ?? $videoData['Status'] ?? 0);
                }
            }

            Log::info('BunnyUploadVideo: Bunny metadata after upload', [
                'media_id' => $this->video->id,
                'post_id' => $post->id,
                'bunny_video_id' => $bunnyVideoId,
                'status' => $status,
                'length' => $videoData['length'] ?? $videoData['Length'] ?? null,
                'width' => $videoData['width'] ?? $videoData['Width'] ?? null,
                'has_mp4_fallback' => $videoData['hasMP4Fallback'] ?? $videoData['HasMP4Fallback'] ?? null,
                'video_library_id' => $videoData['videoLibraryId'] ?? $videoData['VideoLibraryId'] ?? null,
            ]);

            if ($status === 5) {
                $readableError = $bunnyService->getReadableTranscodingError($videoData) ?? 'Bunny transcoding failed.';
                throw new \Exception($readableError);
            }

            $bunnyService->ensureMp4FallbackEnabled($videoData);
            // 5. Update Media record
            $durationSeconds = (int) ($videoData['length'] ?? $videoData['Length'] ?? 0);
            $videoWidth = (int) ($videoData['width'] ?? $videoData['Width'] ?? 0);

            // Bunny can briefly report a completed status while the length
            // field is still zero. Retry a few times before leaving duration blank.
            if ($durationSeconds <= 0) {
                for ($i = 0; $i < 6; $i++) {
                    sleep(5);
                    $videoData = $bunnyService->getVideoWithRetry($bunnyVideoId, 3, 1000);
                    $durationSeconds = (int) ($videoData['length'] ?? $videoData['Length'] ?? 0);
                    $videoWidth = (int) ($videoData['width'] ?? $videoData['Width'] ?? 0);

                    if ($durationSeconds > 0) {
                        break;
                    }
                }
            }

            if ($durationSeconds <= 0 || $videoWidth <= 0) {
                $localVideoInfo = $this->probeLocalVideoInfo($localFile);

                if ($durationSeconds <= 0 && !empty($localVideoInfo['duration'])) {
                    $durationSeconds = (int) $localVideoInfo['duration'];
                }

                if ($videoWidth <= 0 && !empty($localVideoInfo['width'])) {
                    $videoWidth = (int) $localVideoInfo['width'];
                }
            }
            
            Media::whereId($this->video->id)->update([
                'duration_video' => $durationSeconds > 0 ? Helper::getDurationInMinutes($durationSeconds) : null,
                'quality_video' => $videoWidth > 0 ? Helper::getResolutionVideo($videoWidth) : null,
                'encoded' => 'yes',
                // 'status' => 'active'
            ]);

            Log::info('BunnyUploadVideo: Media record marked encoded', [
                'media_id' => $this->video->id,
                'post_id' => $post->id,
                'bunny_video_id' => $bunnyVideoId,
                'duration_seconds' => $durationSeconds,
                'duration_video' => $durationSeconds > 0 ? Helper::getDurationInMinutes($durationSeconds) : null,
                'quality_video' => $videoWidth > 0 ? Helper::getResolutionVideo($videoWidth) : null,
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

    protected function probeLocalVideoInfo(string $localFile): array
    {
        try {
            $ffprobe = FFProbe::create([
                'ffmpeg.binaries' => config('laravel-ffmpeg.ffmpeg.binaries', env('FFMPEG_BINARIES', 'ffmpeg')),
                'ffprobe.binaries' => config('laravel-ffmpeg.ffprobe.binaries', env('FFPROBE_BINARIES', 'ffprobe')),
                'timeout' => config('laravel-ffmpeg.timeout', 3600),
            ]);

            $format = $ffprobe->format($localFile);
            $videoStream = $ffprobe->streams($localFile)->videos()->first();

            return [
                'duration' => (int) round((float) ($format->get('duration') ?? 0)),
                'width' => (int) ($videoStream ? ($videoStream->get('width') ?? 0) : 0),
            ];
        } catch (\Throwable $e) {
            Log::warning('BunnyUploadVideo: Local ffprobe fallback failed', [
                'media_id' => $this->videoId,
                'local_file' => $localFile,
                'error' => $e->getMessage(),
            ]);

            return [
                'duration' => 0,
                'width' => 0,
            ];
        }
    }
}
