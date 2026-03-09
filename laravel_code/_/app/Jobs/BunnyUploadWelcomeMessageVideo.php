<?php

namespace App\Jobs;

use Throwable;
use App\Helper;
use App\Models\Notifications;
use App\Models\MediaWelcomeMessage;
use App\Services\BunnyStreamService;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BunnyUploadWelcomeMessageVideo implements ShouldQueue
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
        $video = MediaWelcomeMessage::whereId($this->videoId)->firstOrFail();

        if (!$bunnyService->isConfigured()) {
            throw new \Exception('Bunny Stream is not configured.');
        }

        $localFile = public_path('temp/' . $video->file);
        if (!file_exists($localFile)) {
            $video->update([
                'status' => 'error',
            ]);
            Notifications::send($video->creator_id, $video->creator_id, 25, $video->id);
            return;
        }

        $bunnyVideoId = null;

        try {
            $syncResult = $bunnyService->syncLibrarySettingsFromApp();
            if (config('settings.watermark_on_videos') == 'on' && empty($syncResult['synced'])) {
                throw new \Exception('Bunny watermark sync failed. Configure BUNNY_API_KEY and ensure video library watermark access is enabled.');
            }

            $collectionId = $bunnyService->getOrCreateCollection('WelcomeMessages');
            $bunnyVideoId = $bunnyService->uploadVideo(
                $localFile,
                $video->file_name ?: ('Welcome Message Video #' . $video->id),
                $collectionId
            );
            $videoData = $bunnyService->getVideoWithRetry($bunnyVideoId, 6, 1000);
            $bunnyService->ensureMp4FallbackEnabled($videoData);

            $status = (int) ($videoData['status'] ?? $videoData['Status'] ?? 0);
            if ($status === 5) {
                $readableError = $bunnyService->getReadableTranscodingError($videoData) ?? 'Bunny transcoding failed.';
                throw new \Exception($readableError);
            }

            $durationSeconds = (int) ($videoData['length'] ?? $videoData['Length'] ?? 0);
            $videoWidth = (int) ($videoData['width'] ?? $videoData['Width'] ?? 0);

            $video->update([
                'bunny_video_id' => $bunnyVideoId,
                'video_poster' => $bunnyService->getPosterUrl($bunnyVideoId),
                'duration_video' => $durationSeconds > 0 ? Helper::getDurationInMinutes($durationSeconds) : null,
                'quality_video' => $videoWidth > 0 ? Helper::getResolutionVideo($videoWidth) : null,
                'encoded' => 'yes',
                'status' => 'active',
            ]);

            if (file_exists($localFile)) {
                unlink($localFile);
            }

            Notifications::send($video->creator_id, $video->creator_id, 24, $video->id);
        } catch (\Exception $e) {
            Log::error('BunnyUploadWelcomeMessageVideo: Error - ' . $e->getMessage());

            if ($bunnyVideoId && $bunnyService->isConfigured()) {
                try {
                    $bunnyService->deleteVideo($bunnyVideoId);
                } catch (\Exception $deleteException) {
                    Log::error('BunnyUploadWelcomeMessageVideo: Error deleting orphan Bunny video - ' . $deleteException->getMessage());
                }
            }

            throw $e;
        }
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('BunnyUploadWelcomeMessageVideo: Job failed - ' . $exception->getMessage());

        $video = MediaWelcomeMessage::whereId($this->videoId)->first();
        if (!$video) {
            return;
        }

        if ($video->bunny_video_id) {
            try {
                $bunnyService = app(BunnyStreamService::class);
                if ($bunnyService->isConfigured()) {
                    $bunnyService->deleteVideo($video->bunny_video_id);
                }
            } catch (\Exception $e) {
                Log::error('BunnyUploadWelcomeMessageVideo: Cleanup Bunny delete failed - ' . $e->getMessage());
            }
        }

        $localFile = public_path('temp/' . $video->file);
        if (file_exists($localFile)) {
            unlink($localFile);
        }

        Notifications::send($video->creator_id, $video->creator_id, 25, $video->id);
        $video->delete();
    }
}
