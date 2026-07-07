<?php

namespace App\Jobs;

use Throwable;
use App\Helper;
use App\Models\Messages;
use App\Models\Notifications;
use App\Models\MediaMessages;
use App\Services\BunnyStreamService;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BunnyUploadMessageVideo implements ShouldQueue
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
        $video = MediaMessages::whereId($this->videoId)->firstOrFail();
        $message = Messages::whereId($video->messages_id)->first();

        if (!$message) {
            return;
        }

        if (!$bunnyService->isConfigured()) {
            throw new \Exception('Bunny Stream is not configured.');
        }

        $localFile = public_path('temp/' . $video->file);
        if (!file_exists($localFile)) {
            throw new \Exception('Local file not found.');
        }

        $bunnyVideoId = null;

        try {
            $syncResult = $bunnyService->syncLibrarySettingsFromApp();
            if ($bunnyService->isWatermarkEnabled() && empty($syncResult['synced'])) {
                throw new \Exception('Bunny watermark sync failed. Configure BUNNY_API_KEY and ensure video library watermark access is enabled.');
            }

            $collectionId = $bunnyService->getOrCreateCollection('Messages');
            $bunnyVideoId = $bunnyService->uploadVideo(
                $localFile,
                $video->file_name ?: ('Message Video #' . $video->id),
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

            $sharedVideoData = [
                'bunny_video_id' => $bunnyVideoId,
                'video_poster' => $bunnyService->getPosterUrl($bunnyVideoId),
                'duration_video' => $durationSeconds > 0 ? Helper::getDurationInMinutes($durationSeconds) : null,
                'quality_video' => $videoWidth > 0 ? Helper::getResolutionVideo($videoWidth) : null,
                'encoded' => 'yes',
            ];

            MediaMessages::where('file', $video->file)
                ->whereType('video')
                ->whereNull('vault_id')
                ->update($sharedVideoData);

            if (file_exists($localFile)) {
                unlink($localFile);
            }

            $remaining = MediaMessages::whereMessagesId($video->messages_id)
                ->whereType('video')
                ->whereNull('vault_id')
                ->where('encoded', 'no')
                ->count();

            if ($remaining === 0) {
                $message->update([
                    'created_at' => now(),
                    'updated_at' => now(),
                    'mode' => 'active',
                ]);

                Notifications::send($message->user()->id, $message->user()->id, 10, $video->messages_id);
            }
        } catch (\Exception $e) {
            Log::error('BunnyUploadMessageVideo: Error - ' . $e->getMessage());

            if ($bunnyVideoId && $bunnyService->isConfigured()) {
                try {
                    $bunnyService->deleteVideo($bunnyVideoId);
                } catch (\Exception $deleteException) {
                    Log::error('BunnyUploadMessageVideo: Error deleting orphan Bunny video - ' . $deleteException->getMessage());
                }
            }

            throw $e;
        }
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('BunnyUploadMessageVideo: Job failed - ' . $exception->getMessage());

        $video = MediaMessages::whereId($this->videoId)->first();
        if (!$video) {
            return;
        }

        $message = Messages::whereId($video->messages_id)->first();
        if ($message) {
            $message->update([
                'created_at' => now(),
                'updated_at' => now(),
                'mode' => 'active',
            ]);

            Notifications::send($message->user()->id, $message->user()->id, 21, $video->messages_id);
        }

        if ($video->bunny_video_id) {
            try {
                $bunnyService = app(BunnyStreamService::class);
                if ($bunnyService->isConfigured()) {
                    $bunnyService->deleteVideo($video->bunny_video_id);
                }
            } catch (\Exception $e) {
                Log::error('BunnyUploadMessageVideo: Cleanup Bunny delete failed - ' . $e->getMessage());
            }
        }

        $localFile = public_path('temp/' . $video->file);
        if (file_exists($localFile)) {
            unlink($localFile);
        }

        $video->delete();
    }
}
