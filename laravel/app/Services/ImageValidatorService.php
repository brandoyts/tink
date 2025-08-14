<?php

namespace App\Services;

use App\Contracts\ImageValidatorContract;
use RuntimeException;

class ImageValidatorService implements ImageValidatorContract {
    private array $allowedMimes = ['image/png', 'image/jpeg', 'image/webp'];

    public function validate(string $base64Data): array {
        $binaryImage = base64_decode($base64Data, true);
        if ($binaryImage === false) {
            throw new RuntimeException('Invalid Base64 image data.');
        }

        $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $binaryImage);
        if (!in_array($mime, $this->allowedMimes, true)) {
            throw new RuntimeException("Unsupported image type: {$mime}");
        }

        return [$binaryImage, $mime];
    }
}