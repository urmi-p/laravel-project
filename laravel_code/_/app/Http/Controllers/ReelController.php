<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Reel;
use App\Models\MediaReel;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\Reports;
use App\Services\BunnyStreamService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReelController extends Controller
{
    protected $bunnyStreamService;

    public function __construct(BunnyStreamService $bunnyStreamService)
    {
        $this->bunnyStreamService = $bunnyStreamService;
    }

    public function create()
    {
        abort_if(auth()->user()->verified_id != 'yes' || !config('settings.allow_reels'), 404);

        return view('users.create-reel');
    }

    public function store(Request $request)
    {
        $fileuploader = $request->input('fileuploader-list-media');
        $fileuploader = json_decode($fileuploader, TRUE);

        if (!$fileuploader) {
            return response()->json([
                'success' => false,
                'errors' => ['error' => __('general.please_select_video')],
            ]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        } //<-- Validator

        $reel = Reel::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'type' => $request->type,
        ]);

        // Update status Media (Image/Video)
        if ($fileuploader) {
            MediaReel::whereName($fileuploader[0]['file'])
                ->update([
                    'reels_id' => $reel->id,
                    'status' => true,
                    'duration_video' => $request->duration ? Helper::getDurationInMinutes($request->duration) : null,
                ]);
        }

        $video = MediaReel::whereReelsId($reel->id)->first();
        if (!$video) {
            $this->deleteReelError($reel->id);

            return response()->json([
                'success' => false,
                'failed' => true,
                'errors' => ['error' => __('general.error_occurred')],
            ]);
        }

        if (!$this->bunnyStreamService->isConfigured()) {
            $this->deleteReelError($reel->id);

            return response()->json([
                'success' => false,
                'failed' => true,
                'errors' => ['error' => 'Bunny Stream is not configured.'],
            ]);
        }

        $bunnyVideoId = null;

        try {
            $syncResult = $this->bunnyStreamService->syncLibrarySettingsFromApp();
            if (config('settings.watermark_on_videos') == 'on' && empty($syncResult['synced'])) {
                throw new \Exception('Bunny watermark sync failed. Configure BUNNY_API_KEY and ensure video library watermark access is enabled.');
            }

            $localFile = public_path('temp/' . $video->name);

            if (!file_exists($localFile)) {
                throw new \Exception(__('general.error_occurred'));
            }

            $collectionId = $this->bunnyStreamService->getOrCreateCollection('Reels');
            $bunnyVideoId = $this->bunnyStreamService->uploadVideo(
                $localFile,
                $request->title ?: ('Reel #' . $reel->id),
                $collectionId
            );

            $customPosterPath = null;
            if ($request->video_thumbnail) {
                $customPosterPath = public_path('temp/' . $request->video_thumbnail);
                if (file_exists($customPosterPath)) {
                    $this->bunnyStreamService->setVideoThumbnailFromFile($bunnyVideoId, $customPosterPath);
                }
            }

            $videoData = $this->bunnyStreamService->getVideoWithRetry($bunnyVideoId, 6, 1000);
            $this->bunnyStreamService->ensureMp4FallbackEnabled($videoData);

            $status = (int) ($videoData['status'] ?? $videoData['Status'] ?? 0);
            if ($status === 5) {
                $readableError = $this->bunnyStreamService->getReadableTranscodingError($videoData) ?? 'Bunny transcoding failed.';
                throw new \Exception($readableError);
            }

            $durationSeconds = (int) ($videoData['length'] ?? $videoData['Length'] ?? 0);
            $durationText = $durationSeconds > 0
                ? Helper::getDurationInMinutes($durationSeconds)
                : ($request->duration ? Helper::getDurationInMinutes($request->duration) : null);

            if (file_exists($localFile)) {
                unlink($localFile);
            }

            if ($customPosterPath && file_exists($customPosterPath)) {
                unlink($customPosterPath);
            }

            $video->update([
                'bunny_video_id' => $bunnyVideoId,
                'video_poster' => $this->bunnyStreamService->getPosterUrl($bunnyVideoId),
                'duration_video' => $durationText,
                'status' => true
            ]);

            $reel->update([
                'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'url' => route('reels.section.show', $reel->id)
            ]);
        } catch (\Exception $e) {
            Log::info('Error creating Reel in Bunny: ' . $e->getMessage());

            if ($bunnyVideoId && $this->bunnyStreamService->isConfigured()) {
                try {
                    $this->bunnyStreamService->deleteVideo($bunnyVideoId);
                } catch (\Exception $deleteException) {
                    Log::info('Error deleting orphan Bunny video: ' . $deleteException->getMessage());
                }
            }

            $this->deleteReelError($reel->id);

            return response()->json([
                'success' => false,
                'failed' => true,
                'errors' => ['error' => $e->getMessage()],
            ]);
        }
    }

    protected function deleteReelError($id): void
    {
        $reel = Reel::with(['media'])->whereId($id)->first();

        if ($reel->media) {
            $localFile = public_path('temp/' . $reel->media->name);

            if (file_exists($localFile)) {
                unlink($localFile);
            }

            $reel->media->delete();
        }

        $reel->delete();
    }

    public function destroy($id)
    {
        $pathReels = config('path.reels');
        $reel = Reel::with(['media'])->whereUserId(auth()->id())->whereId($id)->firstOrFail();

        if ($reel->media) {
            if ($reel->media->bunny_video_id && $this->bunnyStreamService->isConfigured()) {
                try{
                    $this->bunnyStreamService->deleteVideo($reel->media->bunny_video_id);
                } catch (\Exception $e) {
                    Log::error('Bunny delete failed for reel in destroy', [
                        'video_id' => $reel->media->bunny_video_id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            if ($reel->media->name && Storage::exists($pathReels . $reel->media->name)) {
                Storage::delete($pathReels . $reel->media->name);
            }
            if ($reel->media->video_poster && !filter_var($reel->media->video_poster, FILTER_VALIDATE_URL)) {
                Storage::delete($pathReels . $reel->media->video_poster);
            }

            $reel->media->delete();
        }

        // Delete Notifications
        Notifications::whereIn('type', [27, 29, 30, 31, 32])
            ->where('target', $reel->id)
            ->delete();

        $reel->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
            ]);
        }

        return back()->withSuccessMessage(__('general.successfully_removed'));
    }

    public function incrementView(Request $request, $id)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $reel = Reel::find($id);

        if ($reel && $reel->user_id != auth()->id() && !auth()->user()->isSuperAdmin()) {
            $reel->increment('views');
        }
    }

    public function getAllPublicReels($reelId = false)
    {
        $reels = Reel::getRandomRecords(25, 100, function ($query) use ($reelId) {
            $query->where('reels.status', 'active')
                ->where('reels.type', 'public')
                ->when($reelId, function ($query) use ($reelId) {
                    $query->where('reels.id', '<>', $reelId);
                })
                ->select('reels.*');
        }, [
            'user:id,name,username,avatar,cover,hide_name',
            'media'
        ]);

        return $reels;
    }

    /*
     * Show a single reel
    */
    public function show($id)
    {
        abort_if(auth()->guest() && config('settings.age_verification_status'), 404);

        $singleReel = auth()->check()
            ? auth()->user()->singleReel($id)
            : Reel::with(['user:id,name,username,avatar,cover,hide_name', 'media'])
            ->whereId($id)
            ->where('status', 'active')
            ->where('type', 'public')
            ->firstOrFail();

        abort_unless($singleReel->user, 404);

        $reels = auth()->check() ? auth()->user()->reels($id) : $this->getAllPublicReels($id);

        return view('reels.reels', [
            'reels' => $reels,
            'reelSingle' => $singleReel
        ]);
    }

    /*
     * Show all reels
    */
    public function showAll()
    {
        $reels = auth()->check() ? auth()->user()->reels() : $this->getAllPublicReels();

        if ($reels->count() == 0) {
            return redirect()->route('home');
        }

        return view('reels.reels', compact('reels'));
    }

    /**
     *  Load more reels via AJAX
     */
    public function loadMore(Request $request)
    {
        if (!$request->ajax()) {
            return redirect()->route('reels.index');
        }

        $reels = auth()->user()->reels();

        // Check if there are more reels available
        $hasMore = $reels->hasMorePages();

        // Transform the data to send in JSON format
        $formattedReels = [
            'reels' => $reels->map(function ($reel) {
                return [
                    'id' => $reel->id,
                    'media' => [
                        'video' => $this->getReelVideoUrl($reel->media),
                        'video_poster' => $this->getReelThumbnailUrl($reel->media),
                        'duration_video' => $reel->media->duration_video
                    ],
                    'user' => [
                        'name' => $reel->user->name,
                        'avatar' => Helper::getFile(config('path.avatar') . $reel->user->avatar)
                    ]
                ];
            })->toArray()
        ];

        return response()->json([
            'reels' => $formattedReels,
            'has_more' => $hasMore
        ]);
    }

    protected function getReelVideoUrl($media): string
    {
        return Helper::reelPlaybackUrl($media);
    }

    protected function getReelThumbnailUrl($media): string
    {
        return Helper::reelThumbnailUrl($media);
    }

    public function update(Request $request, $id)
    {
        $reel = Reel::whereUserId(auth()->id())->whereId($id)->firstOrFail();

        $request->validate([
            'title' => 'max:100'
        ]);

        $reel->update([
            'title' => $request->title
        ]);

        return back()->withSuccessMessage(__('admin.success_update'));
    }

    public function report(Request $request)
    {
        $data = Reports::firstOrNew([
            'user_id' => auth()->id(),
            'report_id' => $request->id,
            'type' => 'reels'
        ]);

        $validator = Validator::make($request->all(), [
            'reason' => 'required|in:spoofing,copyright,privacy_issue,violent_sexual,spam,fraud',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        }

        if ($data->exists) {
            return response()->json([
                'success' => false,
                'errors' => ['error' => __('general.already_sent_report')],
            ]);
        } else {
            $data->reason = $request->reason;
            $data->save();

            return response()->json([
                'success' => true,
                'text' => __('general.reported_success'),
            ]);
        }
    }
}
