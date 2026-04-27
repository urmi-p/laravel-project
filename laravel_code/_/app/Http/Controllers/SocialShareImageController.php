<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SocialShareImageController extends Controller
{
    public function __invoke(): Response
    {
        $width = 1200;
        $height = 630;

        $canvas = imagecreatetruecolor($width, $height);

        imagealphablending($canvas, true);
        imagesavealpha($canvas, true);

        $this->paintBackground($canvas, $width, $height);
        $this->paintLogoCard($canvas, $width, $height);

        ob_start();
        imagepng($canvas);
        $image = ob_get_clean();

        imagedestroy($canvas);

        return response($image, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    private function paintBackground($canvas, int $width, int $height): void
    {
        [$startR, $startG, $startB] = $this->hexToRgb((string) config('settings.theme_color_pwa', '#111827'));
        [$endR, $endG, $endB] = [17, 24, 39];

        for ($y = 0; $y < $height; $y++) {
            $ratio = $height > 1 ? $y / ($height - 1) : 0;
            $red = (int) round($startR + (($endR - $startR) * $ratio));
            $green = (int) round($startG + (($endG - $startG) * $ratio));
            $blue = (int) round($startB + (($endB - $startB) * $ratio));
            $color = imagecolorallocate($canvas, $red, $green, $blue);
            imageline($canvas, 0, $y, $width, $y, $color);
        }

        $glow = imagecolorallocatealpha($canvas, 255, 255, 255, 110);
        imagefilledellipse($canvas, (int) ($width * 0.18), (int) ($height * 0.2), 280, 280, $glow);
        imagefilledellipse($canvas, (int) ($width * 0.82), (int) ($height * 0.78), 360, 360, $glow);

        $accent = imagecolorallocatealpha($canvas, 255, 255, 255, 118);
        imagefilledellipse($canvas, (int) ($width * 0.78), (int) ($height * 0.2), 180, 180, $accent);
        imagefilledellipse($canvas, (int) ($width * 0.24), (int) ($height * 0.82), 220, 220, $accent);
    }

    private function paintLogoCard($canvas, int $width, int $height): void
    {
        $cardWidth = 560;
        $cardHeight = 520;
        $cardX = (int) (($width - $cardWidth) / 2);
        $cardY = (int) (($height - $cardHeight) / 2);
        $cornerRadius = 40;

        $shadow = imagecolorallocatealpha($canvas, 8, 12, 24, 96);
        $card = imagecolorallocatealpha($canvas, 10, 16, 30, 0);
        $border = imagecolorallocatealpha($canvas, 255, 255, 255, 78);

        $this->drawRoundedRectangle($canvas, $cardX, $cardY + 22, $cardWidth, $cardHeight, $cornerRadius, $shadow);
        $this->drawRoundedRectangle($canvas, $cardX, $cardY, $cardWidth, $cardHeight, $cornerRadius, $card);
        $this->drawRoundedOutline($canvas, $cardX, $cardY, $cardWidth, $cardHeight, $cornerRadius, $border, 2);

        $logoPath = $this->resolveShareImageAssetPath();

        $logo = $this->loadImage($logoPath);
        if (!$logo) {
            return;
        }

        $logoWidth = imagesx($logo);
        $logoHeight = imagesy($logo);

        $maxWidth = 260;
        $maxHeight = 90;
        $scale = min($maxWidth / max($logoWidth, 1), $maxHeight / max($logoHeight, 1), 1.0);

        $targetWidth = max(1, (int) round($logoWidth * $scale));
        $targetHeight = max(1, (int) round($logoHeight * $scale));
        $targetX = (int) (($width - $targetWidth) / 2);
        $targetY = (int) ($cardY + 140);

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

        $this->paintLabel($canvas, $cardX, $cardY, $cardWidth, $cardHeight);

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

    private function paintLabel($canvas, int $cardX, int $cardY, int $cardWidth, int $cardHeight): void
    {
        $description = __('seo.slogan');
        $description = trim($description) !== '' ? trim($description) : 'Exclusive content platform';
        $description = $this->truncateText($description, 38);

        $subtitleColor = imagecolorallocatealpha($canvas, 226, 232, 240, 30);
        $subtitleFont = 3;

        $subtitleWidth = imagefontwidth($subtitleFont) * strlen($description);
        $subtitleX = (int) ($cardX + (($cardWidth - $subtitleWidth) / 2));
        $subtitleY = (int) min($cardY + 335, $cardY + $cardHeight - 54);

        imagestring($canvas, $subtitleFont, $subtitleX, $subtitleY, $description, $subtitleColor);
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
