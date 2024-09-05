<?php

namespace LiveOficial\Pix;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Receiver implements ReceiverContract
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    private function mountBaseUrl(): string
    {
        return "{$this->config['url']}/{$this->config['version']}";
    }

    public function request(): PendingRequest
    {
        return Http::baseUrl($this->mountBaseUrl())
            ->withHeaders(['x-correlationID' => (string) Str::uuid()])
            ->withToken($this->loadToken())
            ->asJson()
            ->withOptions([
                'cert' => $this->config['path_cert'],
                'ssl_key' => $this->config['path_ssl_key']
            ])
            ->retry(1, 0, function ($exception, $request) {
                if ($exception->response->status() !== 401) {
                    return false;
                }

                $request->withToken($this->loadToken());

                return true;
            });
    }

    protected function loadToken(): string
    {
        $token = Cache::get('TOKEN_PIX');

        if ($token === null) {
            $response = $this->fetchToken();
            Cache::put('TOKEN_PIX', $response->access_token, now()->addSeconds($response->expires_in));
            return $response->access_token;
        }
    }

    protected function fetchToken(): object
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config['url_login']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials&client_id={$this->config['client_id']}&client_secret={$this->config['client_secret']}");
        curl_setopt($ch, CURLOPT_SSLKEY, $this->config['path_ssl_key']);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->config['path_cert']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);

        $response = curl_exec($ch);
        return json_decode($response);
    }
}


