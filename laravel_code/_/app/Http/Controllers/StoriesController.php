<?php

namespace App\Http\Controllers;

use Image;
use App\Helper;
use App\Models\Stories;
use Illuminate\Http\File;
use App\Models\StoryFonts;
use App\Models\StoryViews;
use App\Models\MediaStories;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Notifications;
use Illuminate\Validation\Rule;
use App\Models\StoryBackgrounds;
use App\Services\BunnyStorageService;
use App\Services\BunnyStreamService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StoriesController extends Controller
{
    protected $bunnyStreamService, $bunnyStorageService, $request, $settings;

    public function __construct(AdminSettings $settings, Request $request)
    {
        $this->settings = $settings::first();
        $this->request = $request;
        $this->bunnyStreamService = app(BunnyStreamService::class);
        $this->bunnyStorageService = app(BunnyStorageService::class);
    }

    public function createStoryImage()
    {
        if (
            auth()->check()
            && auth()->user()->verified_id != 'yes'
            || !$this->settings->story_image
        ) {
            abort(404);
        }

        return view('users.create-story');
    }

    public function createStoryText()
    {
        if (
            auth()->check()
            && auth()->user()->verified_id != 'yes'
            || !$this->settings->story_text
        ) {
            abort(404);
        }

        $storyBackgrounds = StoryBackgrounds::all();
        $storyFonts = StoryFonts::all();

        if ($storyFonts->count()) {
            foreach ($storyFonts as $font) {
                $fonts[] = str_replace('+', ' ', $font->name);
            }
            $fonts = implode("|", $fonts);
        }

        return view('users.create-story-text', [
            'storyBackgrounds' => $storyBackgrounds,
            'storyFonts' => $storyFonts,
            'fonts' => $fonts ?? null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeStoryText()
    {
        $findFontExists = StoryFonts::whereName($this->request->font)->select('name')->first();
        StoryBackgrounds::whereName($this->request->background)->firstOrfail();
        !in_array($this->request->color, ['#ffffff', '#000000']) ? abort(404) : '';
        $font = isset($findFontExists->name) ? $findFontExists->name : 'Arial';

        $messages = [
            'text.required' => __('general.please_write_something'),
            'text.max' => __('validation.max', ['attribute' => __('general.text')]),
        ];

        $validator = Validator::make($this->request->all(), [
            'text' => 'required|max:300',
            'background' => 'required',
            'color' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        } //<-- Validator

        $background   = $this->request->background;
        $text         = $this->request->text;
        $image        = public_path('img/stories-bg/') . $background;
        $getExtension = explode('.', $background);
        $extension    = $getExtension[1];
        $path         = config('path.stories');
        $fileName     = time() . str_random(50) . '.' . $extension;
        $img          = Image::read($image)->encodeByExtension($extension);

        $localTempPath = public_path('temp/' . $fileName);
        file_put_contents($localTempPath, (string) $img);

        $storedInBunny = false;
        if ($this->bunnyStorageService->isConfigured()) {
            $storedInBunny = $this->bunnyStorageService->uploadFromLocal($localTempPath, $path . $fileName);
        }

        if (!$storedInBunny) {
            Storage::put($path . $fileName, (string) $img, 'public');
        }

        if (file_exists($localTempPath)) {
            unlink($localTempPath);
        }

        $story = new Stories();
        $story->user_id = auth()->id();
        $story->save();

        MediaStories::create([
            'stories_id' => $story->id,
            'name' => $fileName,
            'type' => 'photo',
            'video_length' => '',
            'video_poster' => '',
            'text' => Helper::lineBreakRemove($text),
            'font_color' => $this->request->color,
            'font' => $font,
            'status' => true,
            'created_at' => now()
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $fileuploader = $this->request->input('fileuploader-list-media');
        $fileuploader = json_decode($fileuploader, TRUE);

        if (!$fileuploader) {
            return response()->json([
                'success' => false,
                'errors' => ['error' => __('general.select_media_story')],
            ]);
        }

        $validator = Validator::make($this->request->all(), [
            'title' => 'max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        } //<-- Validator

        $story = new Stories();
        $story->user_id = auth()->id();
        $story->title = $this->request->title;
        $story->save();

        // Update status Media (Image/Video)
        if ($fileuploader) {
            MediaStories::whereName($fileuploader[0]['file'])
                ->update([
                    'stories_id' => $story->id,
                    'status' => true
                ]);
        }

        $video = MediaStories::whereStoriesId($story->id)->whereType('video')->first();

        if ($video) {
            if (!$this->bunnyStreamService->isConfigured()) {
                $this->deleteStoryError($story->id);

                return response()->json([
                    'success' => false,
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

                $collectionId = $this->bunnyStreamService->getOrCreateCollection('Stories');
                $bunnyVideoId = $this->bunnyStreamService->uploadVideo(
                    $localFile,
                    $this->request->title ?: ('Story #' . $story->id),
                    $collectionId
                );
                $videoData = $this->bunnyStreamService->getVideoWithRetry($bunnyVideoId, 6, 1000);
                $this->bunnyStreamService->ensureMp4FallbackEnabled($videoData);

                $status = (int) ($videoData['status'] ?? $videoData['Status'] ?? 0);
                if ($status === 5) {
                    $readableError = $this->bunnyStreamService->getReadableTranscodingError($videoData) ?? 'Bunny transcoding failed.';
                    throw new \Exception($readableError);
                }

                $durationSeconds = (int) ($videoData['length'] ?? $videoData['Length'] ?? 0);

                if (file_exists($localFile)) {
                    unlink($localFile);
                }

                $video->update([
                    'bunny_video_id' => $bunnyVideoId,
                    'video_poster' => $this->bunnyStreamService->getPosterUrl($bunnyVideoId),
                    'video_length' => $durationSeconds > 0 ? $durationSeconds : null,
                    'status' => true,
                ]);

                Notifications::send($story->user_id, $story->user_id, 17, 0);
            } catch (\Exception $e) {
                Log::info('Error creating Story in Bunny: ' . $e->getMessage());

                if ($bunnyVideoId && $this->bunnyStreamService->isConfigured()) {
                    try {
                        $this->bunnyStreamService->deleteVideo($bunnyVideoId);
                    } catch (\Exception $deleteException) {
                        Log::info('Error deleting orphan Bunny video: ' . $deleteException->getMessage());
                    }
                }

                $this->deleteStoryError($story->id);

                return response()->json([
                    'success' => false,
                    'errors' => ['error' => $e->getMessage()],
                ]);
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pathStories = config('path.stories');
        $story = Stories::with(['media.views'])->whereUserId(auth()->id())->whereId($id)->firstOrFail();
    
        if ($story->media->count()) {
            // Delete Views 
            foreach ($story->media as $media) {
                $media->views()->delete();

                if ($media->bunny_video_id && $this->bunnyStreamService->isConfigured()) {
                    try{
                        $this->bunnyStreamService->deleteVideo($media->bunny_video_id);
                    } catch (\Exception $e) {
                        Log::error('Bunny delete failed for story media in destroy', [
                            'video_id' => $media->bunny_video_id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                // Delete Media files
                if ($media->name) {
                    $this->bunnyStorageService->delete($pathStories . $media->name);
                }
                if ($media->name && Storage::exists($pathStories . $media->name)) {
                    Storage::delete($pathStories . $media->name);
                }
                if ($media->video_poster && !filter_var($media->video_poster, FILTER_VALIDATE_URL)) {
                    $this->bunnyStorageService->delete($pathStories . $media->video_poster);
                    Storage::delete($pathStories . $media->video_poster);
                }
                $media->delete();
            }
        }

        // Delete Notifications
        Notifications::where('type', 17)
            ->where('target', $story->id)
            ->delete();
    
        // Delete Story
        $story->delete();
    
        return response()->json([
            'success' => true
        ]);
    }

    protected function deleteStoryError($id): void
    {
        $pathStories = config('path.stories');
        $story = Stories::with(['media'])->whereId($id)->first();

        if (!$story) {
            return;
        }

        foreach ($story->media as $media) {
            $localFile = public_path('temp/' . $media->name);

            if (file_exists($localFile)) {
                unlink($localFile);
            }
            if ($media->name && Storage::exists($pathStories . $media->name)) {
                Storage::delete($pathStories . $media->name);
            }
            if ($media->name) {
                $this->bunnyStorageService->delete($pathStories . $media->name);
            }
            if ($media->video_poster && !filter_var($media->video_poster, FILTER_VALIDATE_URL)) {
                $this->bunnyStorageService->delete($pathStories . $media->video_poster);
                Storage::delete($pathStories . $media->video_poster);
            }

            $media->delete();
        }

        $story->delete();
    }

    /**
     * Move file to Storage
     */
    protected function moveFileStorage($file, $path)
    {
        $localFile = public_path('temp/' . $file);
        // Move the file...
        Storage::putFileAs($path, new File($localFile), $file);
        // Delete temp file
        unlink($localFile);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return void
     */
    public function insertView($id): void
    {
        StoryViews::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'media_stories_id' => $id
            ],
            [
                'user_id' => auth()->id(),
                'media_stories_id' => $id
            ]
        );
    }

    public function getViews($id)
    {
        $sql = StoryViews::with(['user:id,username,hide_name,verified_id,name,avatar'])->whereMediaStoriesId($id)->latest()->get();
        $data = "";

        if ($sql) {
            foreach ($sql as $user) {
                if (isset($user->user)) {
                    $name = $user->user->hide_name == 'yes' ? $user->user->username : $user->user->name;
                    $verified = $user->user->verified_id == 'yes' ? '<small class="verified"><i class="bi bi-patch-check-fill"></i></small>' : null;

                    $data .= '<div class="card mb-2">
                <div class="list-group list-group-sm list-group-flush">
                  <a href="' . url($user->user->username) . '" class="list-group-item list-group-item-action text-decoration-none p-2">
                    <div class="media">
                     <div class="media-left mr-3 position-relative">
                         <img class="media-object rounded-circle" src="' . Helper::getFile(config('path.avatar') . $user->user->avatar) . '" width="45" height="45">
                     </div>
                     <div class="media-body overflow-hidden">
                       <div class="d-flex justify-content-between align-items-center">
                        <h6 class="media-heading mb-0 text-truncate">
                             ' . $name . ' ' . $verified . '
                         </h6>
                       </div>
                       <p class="text-truncate m-0 w-100 text-left">
                       <small class="timeAgo" data="' . date('c', strtotime($user->created_at)) . '"></small>
                       </p>
                     </div>
                 </div>
                   </a>
                </div>
              </div>';
                }
            }
            return $data;
        }
    }
}
