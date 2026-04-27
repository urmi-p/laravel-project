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
use App\Events\NewPostEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BunnyUploadVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 3600;
    public int $videoId;

    public function __construct(int $videoId)
    {
        $this->videoId = $videoId;
    }

    public function handle(BunnyStreamService $bunnyService): void
    {
        $video = Media::findOrFail($this->videoId);
        $post = Updates::find($video->updates_id);

        if (!$post) {
            return;
        }

        if (!$bunnyService->isConfigured()) {
            throw new \Exception('Bunny Stream is not configured.');
        }

        $localFile = public_path('temp/' . $video->video);
        if (!file_exists($localFile)) {
            throw new \Exception('Local file not found.');
        }

        try {
            $syncResult = $bunnyService->syncLibrarySettingsFromApp();
            if ($bunnyService->isWatermarkEnabled() && empty($syncResult['synced'])) {
                throw new \Exception('Bunny watermark sync failed. Configure BUNNY_API_KEY and ensure video library watermark access is enabled.');
            }

            $collectionId = $bunnyService->getOrCreateCollection('Posts');
            $bunnyVideoId = $bunnyService->uploadVideo(
                $localFile,
                $video->file_name ?: ('Post Video #' . $video->id),
                $collectionId
            );

            $videoData = null;
            for ($i = 0; $i < 12; $i++) {
                $videoData = $bunnyService->getVideoWithRetry($bunnyVideoId, 3, 1000);
                $status = (int) ($videoData['status'] ?? $videoData['Status'] ?? 0);

                if ($status === 4 || $status === 5) {
                    break;
                }

                sleep(5);
            }

            $status = (int) ($videoData['status'] ?? $videoData['Status'] ?? 0);
            if ($status === 5) {
                $readableError = $bunnyService->getReadableTranscodingError($videoData) ?? 'Bunny transcoding failed.';
                throw new \Exception($readableError);
            }

            $bunnyService->ensureMp4FallbackEnabled($videoData);

            $durationSeconds = (int) ($videoData['length'] ?? $videoData['Length'] ?? 0);
            $videoWidth = (int) ($videoData['width'] ?? $videoData['Width'] ?? 0);

            for ($i = 0; $i < 6 && ($durationSeconds <= 0 || $videoWidth <= 0); $i++) {
                sleep(5);
                $videoData = $bunnyService->getVideoWithRetry($bunnyVideoId, 3, 1000);
                $durationSeconds = (int) ($videoData['length'] ?? $videoData['Length'] ?? 0);
                $videoWidth = (int) ($videoData['width'] ?? $videoData['Width'] ?? 0);
            }

            Media::whereId($video->id)->update([
                'video' => $bunnyVideoId,
                'bunny_video_id' => $bunnyVideoId,
                'video_poster' => $bunnyService->getPosterUrl($bunnyVideoId),
                'duration_video' => $durationSeconds > 0 ? Helper::getDurationInMinutes($durationSeconds) : null,
                'quality_video' => $videoWidth > 0 ? Helper::getResolutionVideo($videoWidth) : null,
                'encoded' => 'yes',
            ]);

            if (file_exists($localFile)) {
                @unlink($localFile);
            }

            $settings = AdminSettings::first();
            $remaining = Media::whereUpdatesId($video->updates_id)
                ->where('type', 'video')
                ->where('video_embed', '')
                ->where('encoded', 'no')
                ->count();

            if ($remaining == 0) {
                $statusPost = $post->schedule ? 'schedule' : 'active';
                $statusFinalPost = $settings->auto_approve_post == 'on' ? $statusPost : 'pending';

                $post->update([
                    'status' => $statusFinalPost,
                    'date' => $post->editing ? $post->date : now(),
                ]);

                Notifications::send($video->user_id, $video->user_id, 9, $video->updates_id);

                if ($statusFinalPost == 'active' && !$settings->disable_new_post_notification) {
                    event(new NewPostEvent($post));
                }
            }

            if ($settings->moderation_status) {
                $encodedVideo = Media::whereId($video->id)
                    ->where('encoded', 'yes')
                    ->first();

                if ($encodedVideo) {
                    dispatch(new MediaModeration($encodedVideo));
                }
            }
        } catch (\Exception $e) {
            Log::error('BunnyUploadVideo error: ' . $e->getMessage(), [
                'media_id' => $video->id,
                'post_id' => $post->id,
            ]);

            throw $e;
        }
    }

    public function failed(?Throwable $exception): void
    {
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
