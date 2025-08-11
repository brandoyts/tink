<?php

namespace App\Contracts;

interface GeneratorContract {
    public function generate(string $prompt): ?array;
}