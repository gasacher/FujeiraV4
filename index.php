<?php

/**
 * Front controller for Hostinger when the whole project is uploaded into public_html.
 * A normal Laravel setup (document root = public/) does not use this file.
 */
$uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/');

if (preg_match('#(^|/)\.#', $uri) || str_contains($uri, '..')) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "No encontrado";

    return;
}

$publicRoot = realpath(__DIR__.'/public');
$candidate = realpath(__DIR__.'/public'.$uri);

if ($uri !== '/' && $candidate !== false && $publicRoot !== false && is_file($candidate)) {
    $insidePublic = str_starts_with($candidate, $publicRoot.DIRECTORY_SEPARATOR);

    if ($insidePublic && ! str_ends_with(strtolower($candidate), '.php')) {
        $types = [
            'css' => 'text/css; charset=UTF-8',
            'js' => 'text/javascript; charset=UTF-8',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'txt' => 'text/plain; charset=UTF-8',
            'xml' => 'application/xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
        ];
        $extension = strtolower(pathinfo($candidate, PATHINFO_EXTENSION));

        if (isset($types[$extension])) {
            header('Content-Type: '.$types[$extension]);
        }

        readfile($candidate);

        return;
    }
}

require __DIR__.'/public/index.php';
