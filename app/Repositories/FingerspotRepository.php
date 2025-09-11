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

    public function getAttendances(array $params = []): array {
        $params['trans_id'] = 1;
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

    public function getUserInfo(array $params = []): array {
        $params['trans_id'] = 2;
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

    public function getAllPin(array $params): array {
        $params['trans_id'] = 5;
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type'  => 'application/json',
        ])
        ->withOptions([
            'verify' => false, // to be equal to CURLOPT_SSL_VERIFYPEER = 0
        ])
        ->post("{$this->baseUrl}/get_all_pin", $params);

        if ($response->failed()) {
            throw new \Exception("Fingerspot API error: " . $response->body());
        }

        return $response->json();
    }

}
