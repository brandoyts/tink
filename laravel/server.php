<?php

/**
 * Custom server router for Vercel + Laravel
 *
 * - Skips when running "php artisan serve"
 * - Serves static files (CSS, JS, images) directly
 * - Serves /storage/... only in local development
 * - Fallback to Laravel's index.php for all other routes
 */

// Skip Laravel dev server
if (php_sapi_name() === 'cli-server') {
    return false;
}

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '');
$publicPath = __DIR__ . '/public';
$filePath = realpath($publicPath . $uri);

// Serve static files if they exist
if ($filePath !== false && str_starts_with($filePath, $publicPath) && is_file($filePath)) {
    header('Content-Type: ' . get_mime_type($filePath));
    readfile($filePath);
    exit;
}

// Serve /storage/... only in local development
if (app()->environment('local') && strpos($uri, '/storage/') === 0) {
    $storagePath = __DIR__ . '/storage/app/public/' . substr($uri, 9); // remove "/storage/"
    if (file_exists($storagePath)) {
        header('Content-Type: ' . get_mime_type($storagePath));
        readfile($storagePath);
        exit;
    }
}

// Fallback to Laravel index.php
require_once $publicPath . '/index.php';

/**
 * Get MIME type by file extension
 */
function get_mime_type($filename)
{
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    $mimes = [
        'txt' => 'text/plain',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'webp' => 'image/webp',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',
        'ttf' => 'application/x-font-ttf',
        'woff' => 'application/x-woff',
        'woff2' => 'font/woff2',
        'otf' => 'font/otf',
    ];

    return $mimes[$extension] ?? 'application/octet-stream';
}
