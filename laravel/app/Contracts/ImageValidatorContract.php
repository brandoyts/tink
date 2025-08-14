<?php

namespace App\Contracts;

interface ImageValidatorContract {
    public function validate(string $base64Data): array;
}