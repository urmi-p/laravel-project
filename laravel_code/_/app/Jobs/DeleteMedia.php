<?php

namespace App\Jobs;

use App\Models\Media;
use App\Models\Messages;
use App\Models\MediaReel;
use App\Models\VideoCall;
use App\Models\MediaStories;
use App\Models\MediaMessages;
use App\Models\MediaWelcomeMessage;
use Illuminate\Bus\Queueable;
use App\Enums\VideoCallStatus;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\AdminSettings as Setting;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Services\BunnyStorageService;

class DeleteMedia implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public function handle()
  {
    $path      = config('path.images');
    $pathVideo = config('path.videos');
    $pathMusic = config('path.music');
    $pathFile  = config('path.files');
    $pathMessages = config('path.messages');
    $pathStories = config('path.stories');
    $pathReels = config('path.reels');
    $pathWelcomeMessages = config('path.welcome_messages');
    $bunnyStorageService = app(BunnyStorageService::class);

    // Files Media Post
    $files = Media::whereUpdatesId(0)->get();
    $bunnyStreamService = app(\App\Services\BunnyStreamService::class);

    foreach ($files as $media) {
      $dateOriginalPlusMinutes = $media->created_at->addHours(3);

      if (now() > $dateOriginalPlusMinutes) {
        if ($media->image) {
          Storage::delete($path . $media->image);
          $bunnyStorageService->delete($path . $media->image);
          $media->delete();
        }

        if ($media->video) {
          if ($media->bunny_video_id && $bunnyStreamService->isConfigured()) {
            try {
              $bunnyStreamService->deleteVideo($media->bunny_video_id);
            } catch (\Exception $e) {
              Log::error('Bunny delete failed for post media', [
                'video_id' => $media->bunny_video_id,
                'error' => $e->getMessage()
              ]);
            }
          }
          if ($media->video && Storage::exists($pathVideo . $media->video)) {
            Storage::delete($pathVideo . $media->video);
          }
          if ($media->video_poster && !filter_var($media->video_poster, FILTER_VALIDATE_URL)) {
            Storage::delete($pathVideo . $media->video_poster);
          }
          $media->delete();
        }

        if ($media->music) {
          Storage::delete($pathMusic . $media->music);
          $media->delete();
        }

        if ($media->file) {
          Storage::delete($pathFile . $media->file);
          $media->delete();
        }
      } // dateOriginalPlusMinutes
    }

    // File Media Messages
    $filesMessages = MediaMessages::whereMessagesId(0)->get();
    $bunnyStreamService = app(\App\Services\BunnyStreamService::class);

    foreach ($filesMessages as $media) {
      $dateOriginalPlusMinutes = $media->created_at->addHours(3);
      if (now() > $dateOriginalPlusMinutes) {
        if ($media->bunny_video_id && $bunnyStreamService->isConfigured()) {
          try {
            $bunnyStreamService->deleteVideo($media->bunny_video_id);
          } catch (\Exception $e) {
            Log::error('Bunny delete failed for message media', [
              'video_id' => $media->bunny_video_id,
              'error' => $e->getMessage()
            ]);
          }
        }
        if ($media->file && Storage::exists($pathMessages . $media->file)) {
          Storage::delete($pathMessages . $media->file);
        }
        if ($media->file) {
          $bunnyStorageService->delete($pathMessages . $media->file);
        }
        if ($media->video_poster && !filter_var($media->video_poster, FILTER_VALIDATE_URL)) {
          Storage::delete($pathMessages . $media->video_poster);
          $bunnyStorageService->delete($pathMessages . $media->video_poster);
        }
        $media->delete();
      }
    }

    // File Media Stories
    $filesStories = MediaStories::whereStoriesId(0)->get();
    $bunnyStreamService = app(\App\Services\BunnyStreamService::class);

    foreach ($filesStories as $media) {
      $dateOriginalPlusMinutes = $media->created_at->addHours(3);
      if (now() > $dateOriginalPlusMinutes) {

        if ($media->bunny_video_id && $bunnyStreamService->isConfigured()) {
          try {
            $bunnyStreamService->deleteVideo($media->bunny_video_id);
          } catch (\Exception $e) {
            Log::error('Bunny delete failed', [
              'video_id' => $media->bunny_video_id,
              'error' => $e->getMessage()
            ]);
          }
        }
        if ($media->name && Storage::exists($pathStories . $media->name)) {
          Storage::delete($pathStories . $media->name);
        }
        if ($media->name) {
          $bunnyStorageService->delete($pathStories . $media->name);
        }
        if ($media->video_poster && !filter_var($media->video_poster, FILTER_VALIDATE_URL)) {
          $bunnyStorageService->delete($pathStories . $media->video_poster);
          Storage::delete($pathStories . $media->video_poster);
        }
        $media->delete();
      }
    }

    // File Media Reels
    $filesReels = MediaReel::whereReelsId(0)->get();
    $bunnyStreamService = app(\App\Services\BunnyStreamService::class);
    foreach ($filesReels as $media) {
      $dateOriginalPlusMinutes = $media->created_at->addHours(3);
      if (now() > $dateOriginalPlusMinutes) {

        if ($media->bunny_video_id && $bunnyStreamService->isConfigured()) {
          try {
            $bunnyStreamService->deleteVideo($media->bunny_video_id);
          }catch (\Exception $e) {
            Log::error('Bunny delete failed', [
              'video_id' => $media->bunny_video_id,
              'error' => $e->getMessage()
            ]);
          }
        }

        if ($media->name && Storage::exists($pathReels . $media->name)) {
          Storage::delete($pathReels . $media->name);
        }
        if ($media->video_poster && !filter_var($media->video_poster, FILTER_VALIDATE_URL)) {
          Storage::delete($pathReels . $media->video_poster);
        }
        $media->delete();
      }
    }

    // File Media Welcome Messages
    $filesWelcome = MediaWelcomeMessage::whereStatus('pending')
      ->orWhere('status', 'encode')
      ->get();

    $bunnyStreamService = app(\App\Services\BunnyStreamService::class);
    foreach ($filesWelcome as $media) {
      $dateOriginalPlusMinutes = $media->created_at->addHours(3);
      if (now() > $dateOriginalPlusMinutes) {
        if ($media->bunny_video_id && $bunnyStreamService->isConfigured()) {
          try {
            $bunnyStreamService->deleteVideo($media->bunny_video_id);
          } catch (\Exception $e) {
            Log::error('Bunny delete failed for welcome message media', [
              'video_id' => $media->bunny_video_id,
              'error' => $e->getMessage()
            ]);
          }
        }
        if ($media->file && Storage::exists($pathWelcomeMessages . $media->file)) {
          Storage::delete($pathWelcomeMessages . $media->file);
        }
        if ($media->file) {
          $bunnyStorageService->delete($pathWelcomeMessages . $media->file);
        }
        if ($media->video_poster && !filter_var($media->video_poster, FILTER_VALIDATE_URL)) {
          Storage::delete($pathWelcomeMessages . $media->video_poster);
          $bunnyStorageService->delete($pathWelcomeMessages . $media->video_poster);
        }
        $media->delete();
      }
    }

    // Delete files on Local folder 'temp'
    try {
      collect(Storage::disk('default')->listContents('temp', true))
        ->each(function ($file) {
          if ($file['type'] == 'file' && $file['lastModified'] < now()->subHours(3)->getTimestamp()) {
            Storage::disk('default')->delete($file['path']);
          }
        });
    } catch (\Exception $e) {
    }

    // Delete Messages last 6 months
    if (Setting::value('delete_old_messages')) {
      $getLastMessages = Messages::where('created_at', '<', now()->subMonths(6)->format('Y-m-d'))->get();

      if ($getLastMessages->isNotEmpty()) {
        foreach ($getLastMessages as $message) {

          foreach ($message->media as $media) {
            if ($media->bunny_video_id && $bunnyStreamService->isConfigured()) {
              try {
                $bunnyStreamService->deleteVideo($media->bunny_video_id);
              } catch (\Exception $e) {
                Log::error('Bunny delete failed for old message media', [
                  'video_id' => $media->bunny_video_id,
                  'error' => $e->getMessage()
                ]);
              }
            }
            if ($media->file && Storage::exists(config('path.messages') . $media->file)) {
              Storage::delete(config('path.messages') . $media->file);
            }
            if ($media->file) {
              $bunnyStorageService->delete(config('path.messages') . $media->file);
            }
            if ($media->video_poster && !filter_var($media->video_poster, FILTER_VALIDATE_URL)) {
              Storage::delete(config('path.messages') . $media->video_poster);
              $bunnyStorageService->delete(config('path.messages') . $media->video_poster);
            }

            $media->delete();
          }

          $message->delete();
        }
      }
    }

    // Delete Video Calls Rejected or Canceled
     $videoCalls = VideoCall::whereIn('status', [
       VideoCallStatus::REJECTED,
       VideoCallStatus::CANCELED,
       VideoCallStatus::UNANSWERED
     ])->get();

     if ($videoCalls->isNotEmpty()) {
       foreach ($videoCalls as $videoCall) {
         $videoCall->delete();
       }
     }
  }
}
