<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EHRMService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $email;
    protected string $password;
    protected int $tokenTtl;

    public function __construct()
    {
        $this->baseUrl = config('ehrm.base_url');
        $this->apiKey = config('ehrm.api_key');
        $this->email = config('ehrm.email');
        $this->password = config('ehrm.password');
        $this->tokenTtl = config('ehrm.token_ttl', 82800);
    }

    /**
     * Get session token (from cache or fresh login)
     */
    public function getSessionToken(): ?string
    {
        // Check if manual token is provided (for testing)
        $manualToken = config('ehrm.manual_token');
        if ($manualToken) {
            return $manualToken;
        }

        return Cache::remember('ehrm_session_token', $this->tokenTtl, function () {
            return $this->login();
        });
    }

    /**
     * Login to EHRM API and get session token
     */
    protected function login(): ?string
    {
        try {
            Log::info('EHRM Login attempt', [
                'url' => "{$this->baseUrl}/user/login",
                'email' => $this->email,
            ]);

            $response = Http::post("{$this->baseUrl}/user/login", [
                'email' => $this->email,
                'password' => $this->password,
            ]);

            Log::info('EHRM Login response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $token = $data['session_token'] ?? null;

                if ($token) {
                    Log::info('EHRM Login successful, token obtained');
                } else {
                    Log::warning('EHRM Login response missing session_token');
                }

                return $token;
            }

            Log::error('EHRM Login failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('EHRM Login exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Clear cached session token (for re-login)
     */
    public function clearToken(): void
    {
        Cache::forget('ehrm_session_token');
    }

    /**
     * Get pegawai data by NIP
     */
    public function getPegawaiByNip(string $nip): ?array
    {
        $token = $this->getSessionToken();

        if (!$token) {
            return null;
        }

        try {
            Log::info('EHRM Get Pegawai request', [
                'url' => "{$this->baseUrl}/v1/ehrm/data-pegawai",
                'nip' => $nip,
                'api_key' => substr($this->apiKey, 0, 20) . '...',
                'token' => substr($token, 0, 20) . '...',
            ]);

            $response = Http::withHeaders([
                'X-DreamFactory-Api-Key' => $this->apiKey,
                'X-DreamFactory-Session-Token' => $token,
            ])->get("{$this->baseUrl}/v1/ehrm/data-pegawai", [
                        'nip' => $nip,
                    ]);

            Log::info('EHRM Get Pegawai response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Check if resource exists and has data
                if (isset($data['resource']) && count($data['resource']) > 0) {
                    $pegawai = $data['resource'][0];

                    // Return only the fields we need
                    return [
                        'nama' => $pegawai['nama'] ?? null,
                        'nip' => $pegawai['nipbaru'] ?? $nip,
                        'jabatan' => $pegawai['jabatan'] ?? null,
                        'golongan' => $pegawai['golongan'] ?? null,
                        'eselon' => $pegawai['eselon'] ?? null,
                        'tgllahir' => $pegawai['tgllahir'] ?? null,
                        'email' => $pegawai['email'] ?? null,
                        'kdunit' => $pegawai['kdunit'] ?? null,
                        'nama_unit' => $pegawai['nama_unit'] ?? null,
                        'unker' => $pegawai['unker'] ?? null,
                        'kdunor' => $pegawai['kdunor'] ?? null,
                        'unor' => $pegawai['unor'] ?? null,
                    ];
                }

                return null;
            }

            // If unauthorized, try to re-login
            if ($response->status() === 401) {
                $this->clearToken();
                return $this->getPegawaiByNip($nip);
            }

            Log::error('EHRM Get Pegawai failed', [
                'nip' => $nip,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('EHRM Get Pegawai exception', [
                'nip' => $nip,
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
