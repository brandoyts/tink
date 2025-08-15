<?php


namespace App\Services;

use App\Contracts\ImageStorageContract;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicImageStorageService implements ImageStorageContract
{
    private string $defaultMime = "png";

    private array $extensionMap = [
        'image/png'  => 'png',
        'image/jpeg' => 'jpg',
        'image/webp' => 'webp',
    ];

    public function store(string $binaryImage, string $mime): string
    {
        $extension = $this->extensionMap[$mime] ?? $this->defaultMime;
        $fileName  = 'generated/' . Str::uuid() . '.' . $extension;

        // Use 'leapcell' disk in production, 'public' disk locally
        $disk = app()->environment('production') ? 'leapcell' : 'public';

        // Save the file
        Storage::disk($disk)->put($fileName, $binaryImage);

        // Generate public URL
        if ($disk === 'leapcell') {
            // Make sure endpoint does not have trailing slash
            $endpoint = rtrim(config('filesystems.disks.leapcell.cdn'), '/');
            return "{$endpoint}/{$fileName}";
        }

        // Local public storage
        return asset('storage/' . $fileName);
    }
}
