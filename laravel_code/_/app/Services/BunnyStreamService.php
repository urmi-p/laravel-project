<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class BunnyStreamService
{
    protected $apiKey;
    protected $managementApiKey;
    protected $libraryId;
    protected $cdnHostname;

    public function __construct()
    {
        $this->apiKey = env('BUNNY_STREAM_API_KEY');
        $this->managementApiKey = env('BUNNY_API_KEY');
        $this->libraryId = env('BUNNY_STREAM_LIBRARY_ID');
        $this->cdnHostname = env('BUNNY_STREAM_CDN_HOSTNAME');
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->libraryId) && !empty($this->cdnHostname);
    }

    public function isManagementConfigured(): bool
    {
        return !empty($this->managementApiKey) && !empty($this->libraryId);
    }

    /**
     * Upload a video to Bunny Stream
     *
     * @param string $filePath Full path to the video file
     * @param string $title Title of the video
     * @param string|null $collectionId Optional Bunny Collection ID
     * @return string The Bunny Video GUID
     * @throws Exception
     */
    public function uploadVideo($filePath, $title, $collectionId = null)
    {
        if (!$this->isConfigured()) {
            throw new Exception('Bunny Stream is not configured.');
        }

        if (!file_exists($filePath)) {
            throw new Exception('Bunny Stream: source video file not found.');
        }

        $fileSize = filesize($filePath);
        if ($fileSize === false || $fileSize <= 0) {
            throw new Exception('Bunny Stream: source video file is empty or unreadable.');
        }

        $mimeType = @mime_content_type($filePath);
        if ($mimeType && strpos($mimeType, 'video/') !== 0) {
            throw new Exception('Bunny Stream: uploaded file is not a valid video stream.');
        }

        // 1. Create Video Object
        $payload = ['title' => $title];
        if ($collectionId) {
            $payload['collectionId'] = $collectionId;
        }

        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post("https://video.bunnycdn.com/library/{$this->libraryId}/videos", $payload);

        if (!$response->successful()) {
            Log::error('Bunny Stream: Failed to create video object', ['response' => $response->body()]);
            throw new Exception("Bunny Stream: Failed to create video object.");
        }

        $videoData = $response->json();
        $videoId = $videoData['guid'];

        // 2. Upload Video Content
        $client = new Client([
            'timeout' => 0,
            'http_errors' => false,
        ]);

        $fileStream = fopen($filePath, 'rb');
        $uploadResponse = $client->request('PUT', "https://video.bunnycdn.com/library/{$this->libraryId}/videos/{$videoId}", [
            'headers' => [
                'AccessKey' => $this->apiKey,
                'Content-Type' => 'application/octet-stream',
                'Accept' => 'application/json',
            ],
            'body' => $fileStream,
        ]);

        if ($uploadResponse->getStatusCode() < 200 || $uploadResponse->getStatusCode() >= 300) {
            $responseBody = (string) $uploadResponse->getBody();
            Log::error('Bunny Stream: Failed to upload video content', [
                'status' => $uploadResponse->getStatusCode(),
                'response' => $responseBody,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
            ]);
            throw new Exception("Bunny Stream: Failed to upload video content.");
        }

        Log::info('Bunny Stream: Video upload completed', [
            'video_id' => $videoId,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
        ]);

        return $videoId;
    }

    /**
     * Convert Bunny transcoding error to readable message.
     *
     * @param array $videoData
     * @return string|null
     */
    public function getReadableTranscodingError(array $videoData): ?string
    {
        $message = $videoData['transcodingMessages'][0]['message'] ?? $videoData['TranscodingMessages'][0]['Message'] ?? null;
        if (!$message) {
            return null;
        }

        if (stripos($message, 'No compatible streams found') !== false) {
            return 'Bunny could not detect a compatible video stream. Please upload an H.264/AAC MP4 or MOV file.';
        }

        return $message;
    }

    /**
     * Get Bunny video metadata.
     *
     * @param string $videoId
     * @return array
     * @throws Exception
     */
    public function getVideo(string $videoId): array
    {
        if (!$this->isConfigured()) {
            throw new Exception('Bunny Stream is not configured.');
        }

        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey,
            'Accept' => 'application/json',
        ])->get("https://video.bunnycdn.com/library/{$this->libraryId}/videos/{$videoId}");

        if (!$response->successful()) {
            throw new Exception('Bunny Stream: Failed to fetch video metadata.');
        }

        return (array) $response->json();
    }

    /**
     * Try fetching a video metadata with retries.
     *
     * @param string $videoId
     * @param int $attempts
     * @param int $sleepMs
     * @return array
     * @throws Exception
     */
    public function getVideoWithRetry(string $videoId, int $attempts = 5, int $sleepMs = 800): array
    {
        $lastException = null;

        for ($i = 0; $i < $attempts; $i++) {
            try {
                $data = $this->getVideo($videoId);
                if (!empty($data)) {
                    return $data;
                }
            } catch (\Exception $e) {
                $lastException = $e;
            }

            usleep($sleepMs * 1000);
        }

        if ($lastException) {
            throw $lastException;
        }

        throw new Exception('Bunny Stream: Failed to fetch video metadata.');
    }

    /**
     * Set custom thumbnail from local file.
     *
     * @param string $videoId
     * @param string $thumbnailPath
     * @return bool
     */
    public function setVideoThumbnailFromFile(string $videoId, string $thumbnailPath): bool
    {
        if (!$this->isConfigured() || !file_exists($thumbnailPath)) {
            return false;
        }

        $stream = fopen($thumbnailPath, 'r');

        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey,
            'Content-Type' => 'application/octet-stream',
            'Accept' => 'application/json',
        ])->withOptions([
            'body' => $stream
        ])->post("https://video.bunnycdn.com/library/{$this->libraryId}/videos/{$videoId}/thumbnail");

        if (!$response->successful()) {
            Log::warning('Bunny Stream: Failed to set custom thumbnail', [
                'video_id' => $videoId,
                'response' => $response->body()
            ]);
            return false;
        }

        return true;
    }

    /**
     * Ensure Bunny library settings reflect app video settings where possible.
     * Uses Bunny Core API and requires BUNNY_API_KEY.
     *
     * @return array
     */
    public function syncLibrarySettingsFromApp(): array
    {
        if (!$this->isManagementConfigured()) {
            return [
                'synced' => false,
                'reason' => 'management_key_missing'
            ];
        }

        try {
            $library = $this->getVideoLibrary();
            $payload = [];

            if (empty($library['EnableMP4Fallback'])) {
                $payload['EnableMP4Fallback'] = true;
            }

            if (config('settings.watermark_on_videos') == 'on') {
                $payload = array_merge($payload, $this->buildWatermarkPositionPayload());
                $this->ensureWatermarkAsset($library);
            } elseif (!empty($library['HasWatermark'])) {
                $this->deleteWatermark();
            }

            if (!empty($payload)) {
                $this->updateVideoLibrary($payload);
            }

            return ['synced' => true];
        } catch (\Exception $e) {
            Log::warning('Bunny Stream: Failed syncing library settings', [
                'error' => $e->getMessage(),
            ]);
            return [
                'synced' => false,
                'reason' => 'sync_failed'
            ];
        }
    }

    /**
     * Ensure MP4 fallback is available for direct MP4 playback.
     *
     * @param array $videoData
     * @throws Exception
     */
    public function ensureMp4FallbackEnabled(array $videoData): void
    {
        $hasFallback = $videoData['hasMP4Fallback'] ?? $videoData['HasMP4Fallback'] ?? null;
        if (empty($hasFallback)) {
            throw new Exception('Bunny Stream MP4 fallback is disabled for this library/video. Enable MP4 Fallback in Bunny Stream Encoding settings and upload again.');
        }
    }

    /**
     * Delete a video from Bunny Stream
     *
     * @param string $videoId
     * @return bool
     */
    public function deleteVideo($videoId): bool
    {
        $videoId = is_string($videoId) ? trim($videoId) : '';

        if (!$this->isConfigured() || $videoId === '') {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'AccessKey' => $this->apiKey,
                'Accept' => 'application/json',
            ])->delete("https://video.bunnycdn.com/library/{$this->libraryId}/videos/{$videoId}");

            if (!$response->successful()) {
                Log::warning('Bunny Stream: Failed to delete video', [
                    'video_id' => $videoId,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('Bunny Stream: Exception while deleting video', [
                'video_id' => $videoId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get the embed URL for a video
     *
     * @param string $videoId
     * @return string
     */
    public function getEmbedUrl($videoId)
    {
        return "https://iframe.mediadelivery.net/embed/{$this->libraryId}/{$videoId}";
    }

    /**
     * Get the poster URL (thumbnail)
     *
     * @param string $videoId
     * @return string
     */
    public function getPosterUrl($videoId)
    {
        return "https://{$this->cdnHostname}/{$videoId}/thumbnail.jpg";
    }

    /**
     * Core API: get library.
     *
     * @return array
     * @throws Exception
     */
    protected function getVideoLibrary(): array
    {
        $response = Http::withHeaders([
            'AccessKey' => $this->managementApiKey,
            'Accept' => 'application/json',
        ])->get("https://api.bunny.net/videolibrary/{$this->libraryId}");

        if (!$response->successful()) {
            throw new Exception('Bunny Core API: failed to get video library');
        }

        return (array) $response->json();
    }

    /**
     * Core API: update library.
     *
     * @param array $payload
     * @return void
     * @throws Exception
     */
    protected function updateVideoLibrary(array $payload): void
    {
        $response = Http::withHeaders([
            'AccessKey' => $this->managementApiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post("https://api.bunny.net/videolibrary/{$this->libraryId}", $payload);

        if (!$response->successful()) {
            throw new Exception('Bunny Core API: failed to update video library');
        }
    }

    /**
     * Core API: upload watermark asset if needed.
     *
     * @param array $library
     * @return void
     */
    protected function ensureWatermarkAsset(array $library): void
    {
        if (!empty($library['HasWatermark'])) {
            return;
        }

        $watermarkFile = config('settings.watermak_video');
        if (!$watermarkFile) {
            return;
        }

        $watermarkPath = public_path('img/' . $watermarkFile);
        if (!file_exists($watermarkPath)) {
            return;
        }

        $stream = fopen($watermarkPath, 'r');

        $response = Http::withHeaders([
            'AccessKey' => $this->managementApiKey,
            'Content-Type' => 'application/octet-stream',
            'Accept' => 'application/json',
        ])->withOptions([
            'body' => $stream
        ])->put("https://api.bunny.net/videolibrary/{$this->libraryId}/watermark");

        if (!$response->successful()) {
            Log::warning('Bunny Core API: failed to upload watermark asset', [
                'response' => $response->body()
            ]);
        }
    }

    /**
     * Core API: remove watermark.
     *
     * @return void
     */
    protected function deleteWatermark(): void
    {
        Http::withHeaders([
            'AccessKey' => $this->managementApiKey,
            'Accept' => 'application/json',
        ])->delete("https://api.bunny.net/videolibrary/{$this->libraryId}/watermark");
    }

    /**
     * Map app watermark position to Bunny percentage offsets.
     *
     * @return array
     */
    protected function buildWatermarkPositionPayload(): array
    {
        $position = (string) config('settings.watermark_position');

        $left = 5;
        $top = 5;

        if ($position === 'top-right' || $position === 'topright') {
            $left = 75;
            $top = 5;
        } elseif ($position === 'bottom-left' || $position === 'bottomleft') {
            $left = 5;
            $top = 80;
        } elseif ($position === 'bottom-right' || $position === 'bottomright') {
            $left = 75;
            $top = 80;
        }

        return [
            'WatermarkPositionLeft' => $left,
            'WatermarkPositionTop' => $top,
            'WatermarkWidth' => 20,
            'WatermarkHeight' => 20,
        ];
    }

    /**
     * Get or create a collection by name.
     *
     * @param string $name
     * @return string|null The Collection ID
     */
    public function getOrCreateCollection(string $name): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            // 1. List collections
            $response = Http::withHeaders([
                'AccessKey' => $this->apiKey,
                'Accept' => 'application/json',
            ])->get("https://video.bunnycdn.com/library/{$this->libraryId}/collections", [
                'search' => $name
            ]);

            if ($response->successful()) {
                $collections = $response->json()['items'] ?? [];
                foreach ($collections as $collection) {
                    if (strtolower($collection['name']) === strtolower($name)) {
                        return $collection['guid'];
                    }
                }
            }

            // 2. Not found, create it
            $createResponse = Http::withHeaders([
                'AccessKey' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post("https://video.bunnycdn.com/library/{$this->libraryId}/collections", [
                'name' => $name
            ]);

            if ($createResponse->successful()) {
                return $createResponse->json()['guid'];
            }

            Log::error('Bunny Stream: Failed to create collection', ['response' => $createResponse->body()]);
            return null;
        } catch (\Exception $e) {
            Log::warning('Bunny Stream: Error in getOrCreateCollection', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
