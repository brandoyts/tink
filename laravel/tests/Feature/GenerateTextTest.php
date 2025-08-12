<?php

use App\Services\AI\TextGeneratorService;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

beforeEach(function () {
    // Mock the PendingRequest
    $mockPendingRequest = mock(PendingRequest::class);

    // Mock the POST call on PendingRequest
    $mockPendingRequest->shouldReceive('post')
        ->once()
        ->withArgs(function ($url, $payload) {
            return str_contains($url, 'https://googleapis.com')
                && isset($payload['contents']);
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

    // Mock the HttpFactory
    $mockHttp = mock(HttpFactory::class);

    // Make withHeaders return our PendingRequest mock
    $mockHttp->shouldReceive('withHeaders')
        ->andReturn($mockPendingRequest);

    // Instantiate service with mocked HTTP factory
    $this->service = new TextGeneratorService(
        $mockHttp,
        'secret-api-key',
        'https://googleapis.com'
    );
});

test('it generates text using the API', function () {
    $result = $this->service->generate('this is a prompt');

    expect($result['candidates'][0]['content']['parts'][0]['text'])
        ->toBe('Mocked AI response');
});
