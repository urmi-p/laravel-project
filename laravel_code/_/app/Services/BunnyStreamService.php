<?php

namespace App\Services;

use App\Models\AdminSettings;
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
    protected ?array $adminSettingsCache = null;

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
            Log::warning('Bunny Stream: management API key/library not configured for sync');
            return [
                'synced' => false,
                'reason' => 'management_key_missing'
            ];
        }

        try {
            $library = $this->getVideoLibrary();
            $payload = [];
            $watermarkEnabled = $this->isWatermarkEnabled();

            if (empty($library['EnableMP4Fallback'])) {
                $payload['EnableMP4Fallback'] = true;
            }

            if ($watermarkEnabled) {
                $watermarkPositionPayload = $this->buildWatermarkPositionPayload();
                $payload = array_merge($payload, $watermarkPositionPayload);
                $this->ensureWatermarkAsset($library);
            } elseif (!empty($library['HasWatermark'])) {
                $this->deleteWatermark();
            }

            if (!empty($payload)) {
                $this->updateVideoLibrary($payload);
            }

            $updatedLibrary = $this->getVideoLibrary();
            $hasWatermarkAfter = !empty($updatedLibrary['HasWatermark']);

            return [
                'synced' => true,
                'watermark_enabled' => $watermarkEnabled,
                'library_has_watermark' => $hasWatermarkAfter,
            ];
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
            Log::warning('Bunny Core API: update video library failed', [
                'library_id' => $this->libraryId,
                'status' => $response->status(),
                'response' => $this->responseSnippet($response->body()),
            ]);
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

        $watermarkFile = (string) $this->getAppSetting('watermak_video', '');
        if (!$watermarkFile) {
            Log::warning('Bunny Stream: Watermark enabled but watermark file setting is empty');
            return;
        }

        $watermarkPath = public_path('img/' . $watermarkFile);
        if (!file_exists($watermarkPath)) {
            Log::warning('Bunny Stream: Watermark enabled but file not found', [
                'path' => $watermarkPath,
            ]);
            return;
        }

        [$uploadPath, ] = $this->buildSanitizedWatermarkForUpload($watermarkPath);

        $binary = @file_get_contents($uploadPath);
        if ($binary === false || $binary === '') {
            Log::warning('Bunny Watermark Step 4: Failed to read upload file bytes', [
                'upload_path' => $uploadPath,
            ]);
            if ($uploadPath !== $watermarkPath && file_exists($uploadPath)) {
                @unlink($uploadPath);
            }
            return;
        }

        $uploadMd5 = md5($binary);

        $client = new Client([
            'timeout' => 60,
            'http_errors' => false,
        ]);

        $uploadResponse = $client->request('PUT', "https://api.bunny.net/videolibrary/{$this->libraryId}/watermark", [
            'headers' => [
                'AccessKey' => $this->managementApiKey,
                'Content-Type' => 'application/octet-stream',
                'Accept' => 'application/json',
                'Content-Length' => strlen($binary),
            ],
            'body' => $binary,
        ]);

        $status = $uploadResponse->getStatusCode();
        $body = (string) $uploadResponse->getBody();

        if ($status < 200 || $status >= 300) {
            Log::warning('Bunny Core API: failed to upload watermark asset', [
                'status' => $status,
                'response' => $this->responseSnippet($body),
                'upload_md5' => $uploadMd5,
            ]);
        }

        if ($uploadPath !== $watermarkPath && file_exists($uploadPath)) {
            @unlink($uploadPath);
        }
    }

    /**
     * Core API: remove watermark.
     *
     * @return void
     */
    protected function deleteWatermark(): void
    {
        $response = Http::withHeaders([
            'AccessKey' => $this->managementApiKey,
            'Accept' => 'application/json',
        ])->delete("https://api.bunny.net/videolibrary/{$this->libraryId}/watermark");

        if (!$response->successful()) {
            Log::warning('Bunny Stream: Failed to remove existing watermark', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
        }
    }

    /**
     * Map app watermark position to Bunny percentage offsets.
     *
     * @return array
     */
    protected function buildWatermarkPositionPayload(): array
    {
        $position = (string) $this->getAppSetting('watermark_position', 'center');
        $watermarkWidth = 35;
        $watermarkHeight = 35;
        $margin = 5;

        $watermarkFile = (string) $this->getAppSetting('watermak_video', '');
        if ($watermarkFile) {
            $watermarkPath = public_path('img/' . $watermarkFile);
            $meta = $this->getWatermarkImageMeta($watermarkPath);
            $contentWidth = (int) ($meta['content_width'] ?? 0);
            $contentHeight = (int) ($meta['content_height'] ?? 0);
            if ($contentWidth > 0 && $contentHeight > 0) {
                $ratio = $contentHeight / $contentWidth;
                $watermarkHeight = max(20, min(60, (int) round($watermarkWidth * $ratio)));
            }
        }

        $left = $margin;
        $top = $margin;

        if ($position === 'center') {
            $left = (int) floor((100 - $watermarkWidth) / 2);
            $top = (int) floor((100 - $watermarkHeight) / 2);
        } elseif ($position === 'top-right' || $position === 'topright') {
            $left = (int) floor(100 - $watermarkWidth - $margin);
            $top = $margin;
        } elseif ($position === 'bottom-left' || $position === 'bottomleft') {
            $left = $margin;
            $top = (int) floor(100 - $watermarkHeight - $margin);
        } elseif ($position === 'bottom-right' || $position === 'bottomright') {
            $left = (int) floor(100 - $watermarkWidth - $margin);
            $top = (int) floor(100 - $watermarkHeight - $margin);
        }

        $left = max(0, min(100 - $watermarkWidth, $left));
        $top = max(0, min(100 - $watermarkHeight, $top));

        return [
            'WatermarkPositionLeft' => $left,
            'WatermarkPositionTop' => $top,
            'WatermarkWidth' => $watermarkWidth,
            'WatermarkHeight' => $watermarkHeight,
        ];
    }

    /**
     * Check whether watermark on videos is enabled in admin settings.
     *
     * @return bool
     */
    public function isWatermarkEnabled(): bool
    {
        return (string) $this->getAppSetting('watermark_on_videos', 'off') === 'on';
    }

    /**
     * Read app settings from request config first, then DB for queue/non-HTTP contexts.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getAppSetting(string $key, $default = null)
    {
        $configValue = config('settings.' . $key);
        if ($configValue !== null && $configValue !== '') {
            return $configValue;
        }

        if ($this->adminSettingsCache === null) {
            $model = AdminSettings::query()->first();
            $this->adminSettingsCache = $model ? $model->attributesToArray() : [];
        }

        return $this->adminSettingsCache[$key] ?? $default;
    }

    /**
     * Build a temporary sanitized PNG for reliable Bunny watermark upload.
     *
     * @param string $watermarkPath
     * @return array{0:string,1:array}
     */
    protected function buildSanitizedWatermarkForUpload(string $watermarkPath): array
    {
        if (!function_exists('imagecreatefromstring')) {
            return [$watermarkPath, []];
        }

        $raw = @file_get_contents($watermarkPath);
        if ($raw === false || $raw === '') {
            return [$watermarkPath, []];
        }

        $img = @imagecreatefromstring($raw);
        if (!$img) {
            return [$watermarkPath, []];
        }

        $w = imagesx($img);
        $h = imagesy($img);
        imagealphablending($img, false);
        imagesavealpha($img, true);

        $tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'bunny_wm_sanitize_' . md5($watermarkPath . microtime(true)) . '.png';
        $ok = @imagepng($img, $tmp);
        imagedestroy($img);

        if (!$ok || !file_exists($tmp)) {
            return [$watermarkPath, []];
        }

        return [$tmp, [
            'width' => $w,
            'height' => $h,
            'size_bytes' => @filesize($tmp) ?: null,
        ]];
    }

    /**
     * Inspect watermark PNG visibility stats for debugging.
     *
     * @param string $watermarkPath
     * @return array
     */
    protected function getWatermarkImageMeta(string $watermarkPath): array
    {
        $meta = [
            'size_bytes' => @filesize($watermarkPath) ?: null,
            'width' => null,
            'height' => null,
            'transparent_pct' => null,
            'opaque_pct' => null,
            'content_width' => null,
            'content_height' => null,
        ];

        $imageInfo = @getimagesize($watermarkPath);
        if (is_array($imageInfo)) {
            $meta['width'] = $imageInfo[0] ?? null;
            $meta['height'] = $imageInfo[1] ?? null;
        }

        if (function_exists('imagecreatefrompng')) {
            $img = @imagecreatefrompng($watermarkPath);
            if ($img) {
                $w = imagesx($img);
                $h = imagesy($img);
                $total = max(1, $w * $h);
                $transparent = 0;
                $opaque = 0;
                $minX = $w;
                $minY = $h;
                $maxX = -1;
                $maxY = -1;

                for ($y = 0; $y < $h; $y++) {
                    for ($x = 0; $x < $w; $x++) {
                        $rgba = imagecolorat($img, $x, $y);
                        $a = ($rgba & 0x7F000000) >> 24; // 0 = opaque, 127 = transparent
                        if ($a === 127) {
                            $transparent++;
                        } elseif ($a === 0) {
                            $opaque++;
                        }

                        if ($a < 120) {
                            if ($x < $minX) {
                                $minX = $x;
                            }
                            if ($y < $minY) {
                                $minY = $y;
                            }
                            if ($x > $maxX) {
                                $maxX = $x;
                            }
                            if ($y > $maxY) {
                                $maxY = $y;
                            }
                        }
                    }
                }

                $meta['transparent_pct'] = round(($transparent / $total) * 100, 2);
                $meta['opaque_pct'] = round(($opaque / $total) * 100, 2);
                if ($maxX >= $minX && $maxY >= $minY) {
                    $meta['content_width'] = ($maxX - $minX) + 1;
                    $meta['content_height'] = ($maxY - $minY) + 1;
                }
                imagedestroy($img);
            }
        }

        return $meta;
    }

    /**
     * Keep API response logs readable.
     *
     * @param string|null $body
     * @param int $limit
     * @return string|null
     */
    protected function responseSnippet(?string $body, int $limit = 400): ?string
    {
        if ($body === null) {
            return null;
        }

        $clean = trim($body);
        if ($clean === '') {
            return '';
        }

        return mb_substr($clean, 0, $limit);
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
