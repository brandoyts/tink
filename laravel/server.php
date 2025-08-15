<?php

/**
 * Serverless function entry for Vercel + Laravel
 *
 * - Serves static files from /public
 * - Serves /storage/... only in local development
 * - Fallback to Laravel's index.php
 */

// Detect Laravel's built-in dev server (php artisan serve)
if (php_sapi_name() === 'cli-server') {
    return false; // Let the built-in server handle the request
}

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '');
$publicPath = __DIR__ . '/public';
$filePath = $publicPath . $uri;

// Serve static files if they exist
if ($uri !== '/' && file_exists($filePath) && is_file($filePath)) {
    header('Content-Type: ' . get_mime_type($filePath) . '; charset=UTF-8');
    readfile($filePath);
    exit;
}

// Serve /storage/... only in local development
if (app()->environment('local') && strpos($uri, '/storage/') === 0) {
    $storagePath = __DIR__ . '/storage/app/public/' . substr($uri, 9); // remove "/storage/"
    if (file_exists($storagePath)) {
        header('Content-Type: ' . get_mime_type($storagePath) . '; charset=UTF-8');
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
        // images
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
        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',
        // fonts
        'ttf' => 'application/x-font-ttf',
        'woff' => 'application/x-woff',
        'woff2' => 'font/woff2',
        'otf' => 'font/otf',
    ];

    return $mimes[$extension] ?? 'application/octet-stream';
}
