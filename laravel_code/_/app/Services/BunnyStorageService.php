<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BunnyStorageService
{
    protected string $storageZone;
    protected string $storagePassword;
    protected string $storageRegion;
    protected string $pullZoneUrl;

    public function __construct()
    {
        $this->storageZone = (string) env('BUNNY_STORAGE_ZONE', '');
        $this->storagePassword = (string) env('BUNNY_STORAGE_PASSWORD', '');
        $this->storageRegion = (string) env('BUNNY_STORAGE_REGION', '');
        $this->pullZoneUrl = (string) env('BUNNY_PULL_ZONE_URL', '');
    }

    public function isConfigured(): bool
    {
        return $this->storageZone !== '' && $this->storagePassword !== '' && $this->pullZoneUrl !== '';
    }

    public function uploadFromLocal(string $localPath, string $remotePath): bool
    {
        if (!$this->isConfigured() || !file_exists($localPath)) {
            return false;
        }

        $remotePath = $this->normalizePath($remotePath);
        
        try {
            $response = Http::withHeaders([
                'AccessKey' => $this->storagePassword,
                'Content-Type' => 'application/octet-stream',
                'Cache-Control' => 'public, max-age=31536000, immutable',
            ])
            ->timeout(120)
            ->retry(3, 1000)
            ->withBody(file_get_contents($localPath),'application/octet-stream')
            ->put($this->storageApiUrl($remotePath));

            if (!$response->successful()) {
                Log::warning('Bunny Storage: upload failed', [
                    'remote_path' => $remotePath,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('Bunny Storage: upload exception', [
                'remote_path' => $remotePath,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function delete(string $remotePath): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        $remotePath = $this->normalizePath($remotePath);

        try {
            $response = Http::withHeaders([
                'AccessKey' => $this->storagePassword,
            ])->delete($this->storageApiUrl($remotePath));

            if (!$response->successful()) {
                Log::warning('Bunny Storage: delete failed', [
                    'remote_path' => $remotePath,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('Bunny Storage: delete exception', [
                'remote_path' => $remotePath,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function publicUrl(string $remotePath): string
    {
        return rtrim($this->pullZoneUrl, '/') . '/' . ltrim($this->normalizePath($remotePath), '/');
    }

    protected function storageApiUrl(string $remotePath): string
    {
        $url = 'https://' . $this->storageHost() .'/'.$this->storageZone. '/' . ltrim($remotePath, '/');

        Log::info('Bunny Upload URL', ['url' => $url]);

        return $url;
    }

    protected function storageHost(): string
    {
        // if ($this->storageRegion !== '') {
        //     return "{$this->storageRegion}.storage.bunnycdn.com";
        // }

        return "storage.bunnycdn.com";
    }

    protected function normalizePath(string $path): string
    {
        return ltrim(str_replace('\\', '/', $path), '/');
    }
}
