<?php

namespace App\Services\AI;

use App\Abstracts\AiClient;
use App\Contracts\GeneratorContract;
use Illuminate\Http\Client\Factory as HttpFactory;
use RuntimeException;

class ImageGeneratorService extends AiClient implements GeneratorContract
{

    public function __construct(HttpFactory $http, string $apiKey, string $apiUrl)
    {
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
                'generationConfig' => [
                    'responseModalities' => ["TEXT", "IMAGE"]
                ]
            ]
        );

        if ($response->failed()) {
            throw new RuntimeException("image generation API request failed");
        }

        $json = $response->json();


        $inlineData = $this->extractInlineData($json);
        if (!$inlineData || empty($inlineData['data']) || empty($inlineData['mimeType'])) {
            throw new RuntimeException("invalid image data received from AI service");
        }

        return [
            'data'     => $inlineData['data'],
            'mimeType' => $inlineData['mimeType'],
        ];
    }

   

    protected function extractInlineData(array $json): ?array
    {
        $data = data_get($json, 'candidates.0.content.parts.1.inlineData.data');
        $mime = data_get($json, 'candidates.0.content.parts.1.inlineData.mimeType');

        // fallback for possible flat structures
        if (!$data || !$mime) {
            $data = data_get($json, 'image.data') ?? data_get($json, 'data');
            $mime = data_get($json, 'image.mimeType') ?? data_get($json, 'mimeType');
        }

        return ($data && $mime) ? [
            'data' => $data,
            'mimeType' => $mime,
        ] : null;
    }
}
