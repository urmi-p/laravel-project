<?php

namespace App\Actions;

use App\Helper;
use App\Models\User;
use App\Models\Messages;
use App\Models\MediaMessages;
use App\Models\Subscriptions;
use App\Models\MediaWelcomeMessage;
use App\Models\SubscriptionDeleted;
use App\Services\BunnyStorageService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

final class SendWelcomeMessageAction
{
    public function execute(User $creator, $subscriberId)
    {
        if (
            $creator->send_welcome_message
            && SubscriptionDeleted::whereCreatorId($creator->id)->whereUserId($subscriberId)->doesntExist()
            && Subscriptions::whereCreatorId($creator->id)->whereUserId($subscriberId)->count() == 1
        ) {
            try {
                $message = new Messages();
                $message->conversations_id = 0;
                $message->from_user_id = $creator->id;
                $message->to_user_id = $subscriberId;
                $message->message = trim(Helper::checkTextDb($creator->welcome_message_new_subs));
                $message->updated_at = now();
                $message->price = $creator->price_welcome_message ?: 0.00;
                $message->save();

                // Select Media Media Welcome Message of Creator
                $media = MediaWelcomeMessage::whereCreatorId($creator->id)->whereStatus('active')->first();

                if ($media) {
                    MediaMessages::create([
                        'messages_id' => $message->id,
                        'type' => $media->type,
                        'file' => $media->file,
                        'bunny_video_id' => $media->bunny_video_id,
                        'token' => $media->token,
                        'width' => $media->width,
                        'height' => $media->height,
                        'video_poster' => $media->video_poster,
                        'duration_video' => $media->duration_video,
                        'quality_video' => $media->quality_video,
                        'encoded' => $media->encoded,
                        'job_id' => $media->job_id,
                        'status' => 'active',
                        'created_at' => now()
                    ]);

                    if (!$media->bunny_video_id) {
                        $this->copyWelcomeMediaToMessagePath($media->file);
                    }

                    if ($media->video_poster && !filter_var($media->video_poster, FILTER_VALIDATE_URL)) {
                        $this->copyWelcomeMediaToMessagePath($media->video_poster);
                    }
                }
            } catch (\Exception $e) {
                info('Error SendWelcomeMessageAction - ' . $e->getMessage());
            }
        }
    }

    private function copyWelcomeMediaToMessagePath(string $fileName): void
    {
        if (empty($fileName)) {
            return;
        }

        $sourcePath = config('path.welcome_messages') . $fileName;
        $targetPath = config('path.messages') . $fileName;

        if (Storage::exists($sourcePath)) {
            Storage::copy($sourcePath, $targetPath);
            return;
        }

        $bunnyStorageService = app(BunnyStorageService::class);
        if (!$bunnyStorageService->isConfigured()) {
            return;
        }

        try {
            $response = Http::timeout(30)->get($bunnyStorageService->publicUrl($sourcePath));
            if (!$response->successful()) {
                return;
            }

            $tempFile = tempnam(sys_get_temp_dir(), 'wm_');
            if ($tempFile === false) {
                return;
            }

            try {
                file_put_contents($tempFile, $response->body());
                $uploaded = $bunnyStorageService->uploadFromLocal($tempFile, $targetPath);

                if (!$uploaded) {
                    Storage::put($targetPath, $response->body());
                }
            } finally {
                @unlink($tempFile);
            }
        } catch (\Throwable $e) {
            Log::warning('SendWelcomeMessageAction copy failed', [
                'file' => $fileName,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
