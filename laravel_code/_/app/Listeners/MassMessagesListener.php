<?php

namespace App\Listeners;

use App\Helper;
use App\Models\Vault;
use App\Models\Messages;
use Illuminate\Support\Str;
use App\Models\MediaMessages;
use App\Events\MassMessagesEvent;
use App\Jobs\BunnyUploadMessageVideo;
use App\Services\BunnyStreamService;
use Illuminate\Contracts\Queue\ShouldQueue;

class MassMessagesListener implements ShouldQueue
{
  public function __construct() {}

  /**
   * Handle the event.
   *
   * @param  MassMessagesEvent  $event
   * @return void
   */
  public function handle(MassMessagesEvent $event)
  {
    // Get data
    $user = $event->user;
    $fileuploader = $event->fileuploader;
    $messageData = $event->messageData;
    $price = $event->priceMessage;
    $hasFileZip = $event->hasFileZip;
    $file = $event->file;
    $originalName = $event->originalName;
    $size = $event->size;

    $hasFileEpub = $event->hasFileEpub;
    $fileEpub = $event->fileEpub;
    $originalNameEpub = $event->originalNameEpub;
    $sizeEpub = $event->sizeEpub;
    $bunnyStreamService = app(BunnyStreamService::class);
    $pendingVideoUploads = [];
    $temporaryFiles = [];

    // Get Subscriptions Active
    $subscriptionsActive = $user->mySubscriptions()
      ->where('stripe_id', '')
      ->where('ends_at', '>=', now())
      ->orWhere('stripe_status', 'active')
      ->where('stripe_id', '<>', '')
      ->where('creator_id', $user->id)
      ->orWhere('stripe_id', '')
      ->where('creator_id', $user->id)
      ->where('free', 'yes')
      ->get();

    // Send an email notification to all subscribers when there is a new post
    foreach ($subscriptionsActive as $subscriber) {
      $message = new Messages();
      $message->conversations_id = 0;
      $message->from_user_id = $user->id;
      $message->to_user_id = $subscriber->user()->id;
      $message->message = trim(Helper::checkTextDb($messageData));
      $message->updated_at = now();
      $message->price = $price;
      $message->mode = 'active';
      $message->save();

      if ($fileuploader) {
        foreach ($fileuploader as $key => $media) {
          $filename = $media['file'];

          // Parse URL to check if it's from vault
          $parsedUrl = parse_url($filename);
          $isVaultFile = false;

          // Check if it has vault=1 parameter
          if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
            $isVaultFile = isset($queryParams['vault']) && $queryParams['vault'] == '1';
          }

          if ($isVaultFile) {
            // It's a vault file
            $cleanFilename = $parsedUrl['path']; // Get only the path without query params
            if (strpos($cleanFilename, '/') === 0) {
              $cleanFilename = substr($cleanFilename, 1); // Remove initial slash if exists
            }

            // Search for the file in vault table
            $vaultFile = Vault::whereFile($cleanFilename)->first();

            if ($vaultFile) {
              // Create new record in media_messages for vault file
              MediaMessages::create([
                'messages_id' => $message->id,
                'type' => $vaultFile->type,
                'file' => $vaultFile->file,
                'bunny_video_id' => $vaultFile->bunny_video_id,
                'width' => $vaultFile->width,
                'height' => $vaultFile->height,
                'video_poster' => $vaultFile->video_poster,
                'duration_video' => $vaultFile->duration_video,
                'quality_video' => $vaultFile->quality_video,
                'vault_id' => $vaultFile->id,
                'encoded' => 'yes',
                'status' => 'active'
              ]);
            } else {
              \Log::warning("Vault file not found from Mass Messages: {$cleanFilename}");
            }
          } else {
            $sourceMedia = MediaMessages::whereFile($filename)
              ->whereMessagesId(0)
              ->latest('id')
              ->first();

            if (!$sourceMedia) {
              $sourceMedia = MediaMessages::whereFile($filename)
                ->latest('id')
                ->first();
            }

            if (!$sourceMedia) {
              \Log::warning("Mass message source media not found: {$filename}");
              continue;
            }

            $mediaMessage = MediaMessages::create([
              'messages_id' => $message->id,
              'type' => $sourceMedia->type,
              'file' => $sourceMedia->file,
              'bunny_video_id' => $sourceMedia->bunny_video_id,
              'width' => $sourceMedia->width,
              'height' => $sourceMedia->height,
              'video_poster' => $sourceMedia->video_poster,
              'duration_video' => $sourceMedia->duration_video,
              'quality_video' => $sourceMedia->quality_video,
              'file_name' => $sourceMedia->file_name,
              'file_size' => $sourceMedia->file_size,
              'token' => $sourceMedia->token,
              'encoded' => $sourceMedia->encoded,
              'job_id' => $sourceMedia->job_id,
              'vault_id' => $sourceMedia->vault_id,
              'status' => 'active',
              'created_at' => now()
            ]);

            $temporaryFiles[$sourceMedia->file] = $sourceMedia->file;

            if (
              $mediaMessage->type === 'video'
              && !$mediaMessage->vault_id
              && empty($mediaMessage->bunny_video_id)
              && $mediaMessage->encoded !== 'yes'
              && !isset($pendingVideoUploads[$mediaMessage->file])
            ) {
              $pendingVideoUploads[$mediaMessage->file] = $mediaMessage->id;
            }
          }
        }
      } // Fileuploader

      if ($hasFileZip) {
        // We insert the file into the database
        MediaMessages::create([
          'messages_id' => $message->id,
          'type' => 'zip',
          'file' => $file,
          'file_name' => $originalName,
          'file_size' => $size,
          'token' => Str::random(150) . uniqid() . now()->timestamp,
          'status' => 'active',
          'created_at' => now()
        ]);
      }

      if ($hasFileEpub) {
        // We insert the file into the database
        MediaMessages::create([
          'messages_id' => $message->id,
          'type' => 'epub',
          'file' => $fileEpub,
          'file_name' => $originalNameEpub,
          'file_size' => $sizeEpub,
          'token' => Str::random(150) . uniqid() . now()->timestamp,
          'status' => 'active',
          'created_at' => now()
        ]);
      }
    }

    foreach ($pendingVideoUploads as $videoId) {
      if ($bunnyStreamService->isConfigured()) {
        dispatch(new BunnyUploadMessageVideo($videoId));
      }
    }

    if ($temporaryFiles) {
      MediaMessages::whereIn('file', array_values($temporaryFiles))
        ->whereMessagesId(0)
        ->delete();
    }
  }
}
