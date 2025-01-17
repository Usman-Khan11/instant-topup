<?php

namespace App\Services;

use GuzzleHttp\Client;

class ReloadlyService
{
    protected $client;
    protected $authUrl;
    protected $topupUrl;
    protected $giftcardUrl;
    protected $accessToken;

    public function __construct()
    {
        $this->client = new Client();
        $this->authUrl = config('services.reloadly.auth_url', env('RELOADLY_AUTH_URL'));
        $this->topupUrl = config('services.reloadly.topup_url', env('RELOADLY_TOPUP_URL'));
        $this->giftcardUrl = config('services.reloadly.giftcard_url', env('RELOADLY_GIFTCARD_URL'));
    }

    /**
     * Authenticate and get the access token.
     */
    public function authenticate()
    {
        $response = $this->client->post($this->authUrl, [
            'form_params' => [
                'client_id' => env('RELOADLY_CLIENT_ID'),
                'client_secret' => env('RELOADLY_CLIENT_SECRET'),
                'grant_type' => 'client_credentials',
                'audience' => $this->topupUrl,
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $this->accessToken = $data['access_token'];

        return $this->accessToken;
    }

    /**
     * Fetch available countries.
     */
    public function getCountries()
    {
        $this->authenticate();

        $response = $this->client->get("{$this->topupUrl}/countries", [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}",
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Fetch available mobile operators.
     */
    public function getOperators()
    {
        $this->authenticate();

        $response = $this->client->get("{$this->topupUrl}/operators", [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}",
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Send mobile airtime.
     */
    public function sendAirtime($operatorId, $amount, $countryCode, $phoneNumber)
    {
        $this->authenticate();

        $response = $this->client->post("{$this->topupUrl}/topups", [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'operatorId' => $operatorId,
                'amount' => $amount,
                'recipientPhone' => [
                    'countryCode' => $countryCode,
                    'number' => $phoneNumber,
                ],
                'customIdentifier' => uniqid('txn_'),
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Get account balance.
     */
    public function getBalance()
    {
        $this->authenticate();

        $response = $this->client->get("{$this->topupUrl}/accounts/balance", [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}",
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
