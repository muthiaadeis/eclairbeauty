<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FcmService
{
    public function sendToToken(string $token, string $title, string $body, array $data = []): bool
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) return false;

        $projectId = config('services.fcm.project_id');

        $response = Http::withToken($accessToken)->post(
            "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
            [
                'message' => [
                    'token' => $token,
                    'notification' => ['title' => $title, 'body' => $body],
                    'data' => $data,
                ],
            ]
        );

        return $response->successful();
    }

    private function getAccessToken(): ?string
    {
        return Cache::remember('fcm_access_token', 3000, function () {
            $credentials = json_decode(file_get_contents(config('services.fcm.credentials_path')), true);

            $now = time();
            $header = ['alg' => 'RS256', 'typ' => 'JWT'];
            $claims = [
                'iss'   => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud'   => 'https://oauth2.googleapis.com/token',
                'iat'   => $now,
                'exp'   => $now + 3600,
            ];

            $b64 = fn ($d) => rtrim(strtr(base64_encode(is_string($d) ? $d : json_encode($d)), '+/', '-_'), '=');
            $signingInput = $b64($header).'.'.$b64($claims);

            openssl_sign($signingInput, $signature, $credentials['private_key'], 'SHA256');
            $jwt = $signingInput.'.'.$b64($signature);

            $resp = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]);

            return $resp->json('access_token');
        });
    }
}
