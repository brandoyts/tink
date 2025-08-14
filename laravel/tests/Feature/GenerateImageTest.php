<?php

use App\Services\AI\ImageGeneratorService;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use RuntimeException;

beforeEach(function() {
    $this->mockPendingRequest = mock(PendingRequest::class);

    $this->mockHttp = mock(HttpFactory::class);
    $this->mockHttp->shouldReceive("withHeaders")
        ->andReturn($this->mockPendingRequest);
});

test('it generates an image using standard inlineData structure', function () {
    $this->mockPendingRequest->shouldReceive("post")
        ->once()
        ->andReturn(new Response(
            new \GuzzleHttp\Psr7\Response(
                200,
                [],
                json_encode([
                    'candidates' => [
                        [
                            'content' => [
                                'parts' => [
                                    ['text' => 'Mocked AI text'], // TEXT part
                                    [
                                        'inlineData' => [
                                            'data' => base64_encode('fakebinarydata'),
                                            'mimeType' => 'image/png'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ])
            )
        ));

    $service = new ImageGeneratorService(
        $this->mockHttp,
        "secret-api-key",
        "https://test.com"
    );

    $result = $service->generate("generate a 3d image");

    expect($result)
        ->toHaveKeys(['data', 'mimeType'])
        ->and($result['mimeType'])->toBe('image/png')
        ->and(base64_decode($result['data']))->toBe('fakebinarydata');
});

test('it generates an image using fallback flat structure', function () {
    $this->mockPendingRequest->shouldReceive("post")
        ->once()
        ->andReturn(new Response(
            new \GuzzleHttp\Psr7\Response(
                200,
                [],
                json_encode([
                    'image' => [
                        'data' => base64_encode('fallbackbinarydata'),
                        'mimeType' => 'image/jpeg'
                    ]
                ])
            )
        ));

    $service = new ImageGeneratorService(
        $this->mockHttp,
        "secret-api-key",
        "https://test.com"
    );

    $result = $service->generate("generate a 3d image");

    expect($result)
        ->toHaveKeys(['data', 'mimeType'])
        ->and($result['mimeType'])->toBe('image/jpeg')
        ->and(base64_decode($result['data']))->toBe('fallbackbinarydata');
});

test('it throws an exception if image data is missing', function () {
    $this->mockPendingRequest->shouldReceive("post")
        ->once()
        ->andReturn(new Response(
            new \GuzzleHttp\Psr7\Response(
                200,
                [],
                json_encode([
                    'candidates' => [
                        ['content' => ['parts' => [['text' => 'Only text, no image']]]]
                    ]
                ])
            )
        ));

    $service = new ImageGeneratorService(
        $this->mockHttp,
        "secret-api-key",
        "https://test.com"
    );

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage("invalid image data received from AI service");

    $service->generate("generate a 3d image");
});

test('it throws an exception if API request fails', function () {
    $this->mockPendingRequest->shouldReceive("post")
        ->once()
        ->andReturn(new Response(
            new \GuzzleHttp\Psr7\Response(
                500,
                [],
                json_encode(['error' => 'Internal Server Error'])
            )
        ));

    $service = new ImageGeneratorService(
        $this->mockHttp,
        "secret-api-key",
        "https://test.com"
    );

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage("image generation API request failed");

    $service->generate("generate a 3d image");
});
