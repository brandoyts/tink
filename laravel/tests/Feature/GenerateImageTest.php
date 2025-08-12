<?php

use App\Services\AI\ImageGeneratorService;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

beforeEach(function() {
    $mockPendingRequest = mock(PendingRequest::class);

    $mockPendingRequest->shouldReceive("post")
        ->once()
        ->withArgs(function($url, $payload) {
            return str_contains($url, "https://test.com")
                && isset($payload["contents"]);
        })
        ->andReturn(new Response(
            new \GuzzleHttp\Psr7\Response(
                200,
                [],
                json_encode([
                    'candidates' => [
                        ['content' => ['parts' => [['text' => 'Mocked AI response']]]]
                    ]
                ])
            )
        ));

    $mockHttp = mock(HttpFactory::class);
    $mockHttp->shouldReceive("withHeaders")
        ->andReturn($mockPendingRequest);

    $this->service = new ImageGeneratorService(
        $mockHttp,
        "secret-api-key",
        "https://test.com"
    );
});

test('it generates an image', function () {
    $result = $this->service->generate("generate a 3d image");

    expect($result['candidates'][0]['content']['parts'][0]['text'])
        ->toBe('Mocked AI response');
});
