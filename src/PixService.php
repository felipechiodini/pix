<?php

namespace LiveOficial\Pix;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PixService
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function cob(array $data)
    {
        $url = $this->config['url'];
        $version = $this->config['version'];
        $endpoint = "{$url}/{$version}/cob";

        return $this->request()->post($endpoint, $data);
    }

    public function getByTransactionId(string $transactionId)
    {
        $url = $this->config['url'];
        $version = $this->config['version'];
        $endpoint = "{$url}/{$version}/cob/{$transactionId}";

        return $this->request()->get($endpoint);
    }

    public function request(): PendingRequest
    {
        return Http::withHeaders(['x-correlationID' => (string) Str::uuid()])
            ->withToken($this->generateToken())
            ->asJson()
            ->withOptions([
                'cert' => $this->config['path_cert'],
                'ssl_key' => $this->config['path_ssl_key']
            ]);
    }

    private function generateToken(): string
    {
        $clientId = $this->config['client_id'];
        $clientSecret = $this->config['client_secret'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config['url_login']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials&client_id={$clientId}&client_secret={$clientSecret}");
        curl_setopt($ch, CURLOPT_SSLCERT, $this->config['path_cert']);
        curl_setopt($ch, CURLOPT_SSLKEY, $this->config['path_ssl_key']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);

        $response = curl_exec($ch);
        return json_decode($response)->access_token;
    }
}
