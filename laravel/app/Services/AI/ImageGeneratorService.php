<?php

namespace App\Services\AI;

use App\Abstracts\AiClient;
use App\Contracts\GeneratorContract;
use Illuminate\Http\Client\Factory as HttpFactory;

class ImageGeneratorService extends AiClient implements GeneratorContract {

    public function __construct(HttpFactory $http, string $apiKey, string $apiUrl) {
        parent::__construct(
            $http,
            $apiKey,
            $apiUrl
        );
    }

    public function generate(string $prompt): ?array {
        $response = $this->client([
            "x-goog-api-key" => $this->apiKey,
            "Content-Type" => "application/json"
        ])->post(
            $this->apiUrl,
            [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generateConfig' => [
                    'responseModalities' => ['TEXT', 'IMAGE']
                ]
            ]
        );

        $response->throw();

        return $response->json();
    }
}