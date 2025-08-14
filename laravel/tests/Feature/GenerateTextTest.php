<?php

use App\Services\AI\TextGeneratorService;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

beforeEach(function () {
    $mockPendingRequest = mock(PendingRequest::class);

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

    $mockHttp = mock(HttpFactory::class);

    $mockHttp->shouldReceive('withHeaders')
        ->andReturn($mockPendingRequest);

    $this->service = new TextGeneratorService(
        $mockHttp,
        'secret-api-key',
        'https://googleapis.com'
    );
});

test('it generates text using the API', function () {
    $result = $this->service->generate('this is a prompt');
    expect($result["text"])
        ->toBe('Mocked AI response');
});
