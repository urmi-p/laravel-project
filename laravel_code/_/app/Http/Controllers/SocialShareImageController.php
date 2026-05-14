<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SocialShareImageController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $image = $this->renderImage();
        $headers = [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=3600',
            'Content-Length' => (string) strlen($image),
            'Content-Disposition' => 'inline; filename="social-share-image.png"',
            'X-Content-Type-Options' => 'nosniff',
        ];

        if ($request->isMethod('head')) {
            return response('', 200, $headers);
        }

        return response($image, 200, $headers);
    }

    private function renderImage(): string
    {
        $width = 1200;
        $height = 630;

        $canvas = imagecreatetruecolor($width, $height);

        imagealphablending($canvas, true);
        imagesavealpha($canvas, true);

        $this->paintBackground($canvas, $width, $height);
        $this->paintHero($canvas, $width, $height);

        ob_start();
        imagepng($canvas);
        $image = (string) ob_get_clean();

        imagedestroy($canvas);

        return $image;
    }

    private function paintBackground($canvas, int $width, int $height): void
    {
        [$startR, $startG, $startB] = $this->hexToRgb((string) config('settings.theme_color_pwa', '#C92D39'));
        [$endR, $endG, $endB] = [13, 18, 35];

        for ($y = 0; $y < $height; $y++) {
            $ratio = $height > 1 ? $y / ($height - 1) : 0;
            $red = (int) round($startR + (($endR - $startR) * $ratio));
            $green = (int) round($startG + (($endG - $startG) * $ratio));
            $blue = (int) round($startB + (($endB - $startB) * $ratio));
            $color = imagecolorallocate($canvas, $red, $green, $blue);
            imageline($canvas, 0, $y, $width, $y, $color);
        }

        $glow = imagecolorallocatealpha($canvas, 255, 255, 255, 120);
        imagefilledellipse($canvas, (int) ($width * 0.14), (int) ($height * 0.2), 220, 220, $glow);
        imagefilledellipse($canvas, (int) ($width * 0.86), (int) ($height * 0.24), 170, 170, $glow);
        imagefilledellipse($canvas, (int) ($width * 0.82), (int) ($height * 0.82), 320, 320, $glow);

        $sheen = imagecolorallocatealpha($canvas, 255, 255, 255, 122);
        imagefilledrectangle($canvas, 0, 0, $width, 88, $sheen);
    }

    private function paintHero($canvas, int $width, int $height): void
    {
        $logoPath = $this->resolveShareImageAssetPath();
        $logo = $this->loadImage($logoPath);

        if (!$logo) {
            $this->paintFallbackText($canvas, $width, $height);
            return;
        }

        $logoWidth = imagesx($logo);
        $logoHeight = imagesy($logo);

        $maxWidth = 560;
        $maxHeight = 180;
        $scale = min($maxWidth / max($logoWidth, 1), $maxHeight / max($logoHeight, 1), 1.0);

        $targetWidth = max(1, (int) round($logoWidth * $scale));
        $targetHeight = max(1, (int) round($logoHeight * $scale));
        $targetX = (int) (($width - $targetWidth) / 2);
        $targetY = (int) (($height - $targetHeight) / 2) - 40;

        imagealphablending($canvas, true);
        imagesavealpha($canvas, true);
        imagecopyresampled(
            $canvas,
            $logo,
            $targetX,
            $targetY,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $logoWidth,
            $logoHeight
        );

        $this->paintTagline($canvas, $width, $height);

        imagedestroy($logo);
    }

    private function resolveShareImageAssetPath(): string
    {
        $candidates = array_filter([
            public_path('img/' . config('settings.logo')),
            public_path('img/main.png'),
            public_path('img/small-logo.png'),
            public_path('img/' . config('settings.favicon')),
            public_path('images/icons/icon-512x512.png'),
        ]);

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return public_path('images/icons/icon-512x512.png');
    }

    private function paintTagline($canvas, int $width, int $height): void
    {
        $title = trim((string) config('settings.title')) !== '' ? trim((string) config('settings.title')) : 'Close Only';
        $description = __('seo.slogan');
        $description = trim($description) !== '' ? trim($description) : 'Access exclusive content and private interactions';
        $description = $this->truncateText($description, 68);

        $titleFont = 5;
        $subtitleFont = 4;
        $titleColor = imagecolorallocate($canvas, 255, 255, 255);
        $subtitleColor = imagecolorallocate($canvas, 230, 234, 242);
        $dividerColor = imagecolorallocatealpha($canvas, 255, 255, 255, 96);
        $titleWidth = imagefontwidth($titleFont) * strlen($title);
        $subtitleWidth = imagefontwidth($subtitleFont) * strlen($description);

        $titleX = (int) (($width - $titleWidth) / 2);
        $subtitleX = (int) (($width - $subtitleWidth) / 2);
        $titleY = $height - 120;
        $subtitleY = $height - 78;

        imageline($canvas, 250, $height - 142, 950, $height - 142, $dividerColor);
        imagestring($canvas, $titleFont, $titleX, $titleY, $title, $titleColor);
        imagestring($canvas, $subtitleFont, $subtitleX, $subtitleY, $description, $subtitleColor);
    }

    private function paintFallbackText($canvas, int $width, int $height): void
    {
        $title = trim((string) config('settings.title')) !== '' ? trim((string) config('settings.title')) : 'Close Only';
        $title = $this->truncateText($title, 30);
        $description = __('seo.slogan');
        $description = trim($description) !== '' ? trim($description) : 'Exclusive creator platform';
        $description = $this->truncateText($description, 48);

        $titleFont = 5;
        $subtitleFont = 4;
        $titleColor = imagecolorallocate($canvas, 255, 255, 255);
        $subtitleColor = imagecolorallocate($canvas, 232, 236, 245);

        $titleX = (int) (($width - (imagefontwidth($titleFont) * strlen($title))) / 2);
        $subtitleX = (int) (($width - (imagefontwidth($subtitleFont) * strlen($description))) / 2);

        imagestring($canvas, $titleFont, $titleX, 250, $title, $titleColor);
        imagestring($canvas, $subtitleFont, $subtitleX, 290, $description, $subtitleColor);
    }

    private function drawRoundedRectangle($image, int $x, int $y, int $width, int $height, int $radius, int $color): void
    {
        imagefilledrectangle($image, $x + $radius, $y, $x + $width - $radius, $y + $height, $color);
        imagefilledrectangle($image, $x, $y + $radius, $x + $width, $y + $height - $radius, $color);

        imagefilledellipse($image, $x + $radius, $y + $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($image, $x + $width - $radius, $y + $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($image, $x + $radius, $y + $height - $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($image, $x + $width - $radius, $y + $height - $radius, $radius * 2, $radius * 2, $color);
    }

    private function drawRoundedOutline($image, int $x, int $y, int $width, int $height, int $radius, int $color, int $thickness = 1): void
    {
        imagesetthickness($image, $thickness);

        imageline($image, $x + $radius, $y, $x + $width - $radius, $y, $color);
        imageline($image, $x + $radius, $y + $height, $x + $width - $radius, $y + $height, $color);
        imageline($image, $x, $y + $radius, $x, $y + $height - $radius, $color);
        imageline($image, $x + $width, $y + $radius, $x + $width, $y + $height - $radius, $color);

        imagearc($image, $x + $radius, $y + $radius, $radius * 2, $radius * 2, 180, 270, $color);
        imagearc($image, $x + $width - $radius, $y + $radius, $radius * 2, $radius * 2, 270, 360, $color);
        imagearc($image, $x + $radius, $y + $height - $radius, $radius * 2, $radius * 2, 90, 180, $color);
        imagearc($image, $x + $width - $radius, $y + $height - $radius, $radius * 2, $radius * 2, 0, 90, $color);

        imagesetthickness($image, 1);
    }

    private function loadImage(string $path)
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return match ($extension) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'gif' => @imagecreatefromgif($path),
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null,
            default => @imagecreatefrompng($path),
        };
    }

    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = preg_replace('/(.)/', '$1$1', $hex);
        }

        if (!preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
            return [17, 24, 39];
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    private function truncateText(string $value, int $limit): string
    {
        if (function_exists('mb_strlen') && function_exists('mb_substr')) {
            return mb_strlen($value) > $limit
                ? rtrim(mb_substr($value, 0, $limit - 3)) . '...'
                : $value;
        }

        return strlen($value) > $limit
            ? rtrim(substr($value, 0, $limit - 3)) . '...'
            : $value;
    }
}
