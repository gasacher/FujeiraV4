<?php

namespace App\Support;

class Site
{
    public static function origin(): string
    {
        $configured = rtrim((string) config('app.url'), '/');

        if (str_starts_with($configured, 'https://')) {
            return $configured;
        }

        return 'https://fujeiraretrostore.com';
    }

    public static function url(string $path = '/'): string
    {
        if (str_starts_with($path, 'https://') || str_starts_with($path, 'http://')) {
            return preg_replace('#^http://#', 'https://', $path) ?? $path;
        }

        return self::origin().'/'.ltrim($path, '/');
    }

    public static function image(string $path): string
    {
        $webp = preg_replace('/\.(png|jpe?g)$/i', '.webp', $path);

        if (is_string($webp) && is_file(public_path($webp))) {
            $path = $webp;
        }

        $encoded = implode('/', array_map('rawurlencode', explode('/', $path)));

        return asset($encoded);
    }

    public static function imageAbsolute(string $path): string
    {
        $relative = parse_url(self::image($path), PHP_URL_PATH) ?: '/'.$path;

        return self::url($relative);
    }
}
