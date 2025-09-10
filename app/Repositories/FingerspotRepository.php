<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class FingerspotRepository
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct() {
        $this->baseUrl = config('services.fingerspot.base_url');
        $this->apiKey  = config('services.fingerspot.api_key');
    }

    public function getUserInfo(array $params = []): array {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type'  => 'application/json',
        ])
        ->withOptions([
            'verify' => false,
        ])
        ->post("{$this->baseUrl}/get_userinfo", $params);

        if ($response->failed()) {
            throw new \Exception("Fingerspot API error: " . $response->body());
        }

        return $response->json();
    }

    public function getAttendances(array $params = []): array {
        // \Log::info($params);
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type'  => 'application/json',
        ])
        ->withOptions([
            'verify' => false, // to be equal to CURLOPT_SSL_VERIFYHOST & VERIFYPEER = 0
        ])
        ->post("{$this->baseUrl}/get_attlog", $params);

        if ($response->failed()) {
            throw new \Exception("Fingerspot API error: " . $response->body());
        }

        return $response->json();
    }

}
