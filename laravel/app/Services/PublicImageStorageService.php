<?php


namespace App\Services;

use App\Contracts\ImageStorageContract;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicImageStorageService implements ImageStorageContract {
    private string $defaultMime = "png";
    
    private array $extensionMap = [
        'image/png'  => 'png',
        'image/jpeg' => 'jpg',
        'image/webp' => 'webp',
    ];

    public function store(string $binaryImage, string $mime): string {
        $extension = $this->extensionMap[$mime] ?? $this->defaultMime;
        $fileName  = 'generated/' . Str::uuid() . '.' . $extension;

        Storage::disk('public')->put($fileName, $binaryImage);

        return asset('storage/' . $fileName);
    }

}