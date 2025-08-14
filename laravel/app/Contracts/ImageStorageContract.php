<?php

namespace App\Contracts;

interface ImageStorageContract {
    public function store(string $binaryImage, string $mime): string;
}