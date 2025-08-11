<?php

namespace App\Abstracts;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\PendingRequest;

abstract class AiClient {
    protected string $apiKey;
    protected string $apiUrl;
    protected HttpFactory $http;
    
    public function __construct(HttpFactory $http, string $apiKey, string $apiUrl) {
        $this->apiKey  = $apiKey;
        $this->apiUrl = rtrim($apiUrl, '/');
        $this->http    = $http;
    }

    public function client(array $headers = []): PendingRequest {
        return $this->http->withHeaders($headers);
    }
}