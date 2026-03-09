<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Media;
use App\Models\Updates;
use Illuminate\Http\Request;
use App\Models\Notifications;
use Illuminate\Support\Facades\Storage;
use App\Models\AdminSettings as Setting;
use App\Services\BunnyStreamService;
use App\Services\SightEngineVideoValidatorService;

class WebhookSightengineController extends Controller
{
    public function receive(Request $request, SightEngineVideoValidatorService $videoValidation)
    {
        $content = $request->json()->all();

        if ($content['data']['status'] == 'ongoing') {
            return;
        }

        try {
            // Get Video ID
            $media = Media::with(['updates'])->whereId($request->videoId)->first();

            if (!$media) {
                return;
            }

            $post = $media->updates;
            $pathFile = Helper::postPlaybackUrl($media);

            $date = $post->editing ? $post->date : now();

            // Status final post
            $statusPost = $post->schedule ? 'schedule' : 'active';
            $statusFinalPost = Setting::value('auto_approve_post') == 'on' ? $statusPost : 'pending';

            if ($content['data']['status'] == 'finished') {
                // Check if there are other media that have not been moderated
                $mediaPending = Media::whereUpdatesId($post->id)->whereStatus('pending')->count();

                $validation = $videoValidation->validateVideoContent($content);

                if ($validation['approved']) {
                    // Update status of video
                    $post->status = 'active';
                    $post->save();

                    // Update status of post
                    if ($mediaPending == 0 && $post->status == 'pending') {
                        $post->update([
                            'date' => $date,
                            'status' => $statusFinalPost
                        ]);

                        if ($statusFinalPost == 'active') {
                            Notifications::send($post->user_id, 1, 8, $post->id);
                        }
                    }
                } else {
                    // Send notification to user
                    Notifications::send($post->user_id, 1, 33, $post->user_id, $media->file_name);

                    $this->deleteMedia($media, $post, $pathFile, $statusFinalPost);
                }
            }

            // Failure
            if ($content['data']['status'] == 'failure') {
                Notifications::send($post->user_id, 1, 34, 0, $media->file_name);

                $this->deleteMedia($media, $post, $pathFile, $statusFinalPost);
            }

            return response()->noContent(200);
        } catch (\Exception $e) {
            info('Error in WebhookSightengineController receive():', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'fileName' => $media?->file_name,
            ]);
            

            Notifications::send($post->user_id, 1, 34, 0, $media->file_name);

            $this->deleteMedia($media, $post, $pathFile, $statusFinalPost);
        }
    }

    protected function deleteMedia(Media $media, Updates $post, string $pathFile, $statusFinalPost)
    {
        if ($media->bunny_video_id) {
            $bunnyStreamService = app(BunnyStreamService::class);
            if ($bunnyStreamService->isConfigured()) {
                $bunnyStreamService->deleteVideo($media->bunny_video_id);
            }
        }

        if ($pathFile && !filter_var($pathFile, FILTER_VALIDATE_URL)) {
            Storage::delete($pathFile);
        }

        if ($media->video_poster && !filter_var($media->video_poster, FILTER_VALIDATE_URL)) {
            Storage::delete(config('path.videos') . $media->video_poster);
        }

        $getMediaPending = $post->media->where('status', 'pending')->count();

        // Delete post
        if (!$post->editing && $getMediaPending == 0) {
            $post->update([
                'status' => $statusFinalPost
            ]);
        }

        // Delete video
        $media->delete();
    }
}