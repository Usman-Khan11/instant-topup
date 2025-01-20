<?php

namespace App\Services;

use GuzzleHttp\Client;

class ReloadlyService
{
    protected $client;
    protected $clientId;
    protected $clientSecret;
    protected $authUrl;
    protected $topupUrl;
    protected $giftcardUrl;
    protected $utilityPaymentsUrl;
    protected $accessToken;

    public function __construct()
    {
        $this->client = new Client();
        $test_mode = env('RELOADLY_TEST_MODE');

        if ($test_mode == 1) {
            $this->clientId = '21spSJY7lgpIgXvyCHDI8jPOQZiEbbFo';
            $this->clientSecret = 'WQpxgUsZz2-GalPK4cZ1iYCKJQfpZ3-nV0cbfRuWa8xkh78immBQ3vQKwilFQVc';
            $this->authUrl = env('RELOADLY_AUTH_URL');
            $this->topupUrl = 'https://topups-sandbox.reloadly.com';
            $this->giftcardUrl = 'https://giftcards-sandbox.reloadly.com';
            $this->utilityPaymentsUrl = 'https://utilities-sandbox.reloadly.com';
        } else {
            $this->clientId = env('RELOADLY_CLIENT_ID');
            $this->clientSecret = env('RELOADLY_CLIENT_SECRET');
            $this->authUrl = env('RELOADLY_AUTH_URL');
            $this->topupUrl = env('RELOADLY_TOPUP_URL');
            $this->giftcardUrl = env('RELOADLY_GIFTCARD_URL');
            $this->utilityPaymentsUrl = env('RELOADLY_UTILITY_PAYMENTS_URL');
        }
    }

    private function getUrl($type = 'topups')
    {
        if ($type == 'giftcards') {
            $url = $this->giftcardUrl;
        } else if ($type == 'utilities') {
            $url = $this->utilityPaymentsUrl;
        } else {
            $url = $this->topupUrl;
        }

        return $url;
    }

    /**
     * Authenticate and get the access token.
     */
    public function authenticate($type = 'topups')
    {
        $url = $this->getUrl($type);

        try {
            $response = $this->client->post($this->authUrl, [
                'form_params' => [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'client_credentials',
                    'audience' => $url,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $this->accessToken = $data['access_token'];

            return $this->accessToken;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Fetch available countries.
     */
    public function getCountries($type = 'topups')
    {
        $url = $this->getUrl($type);

        try {
            $this->authenticate($type);

            $response = $this->client->get("{$url}/countries", [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $output = ['success' => 1, 'data' => $data];
        } catch (\Exception $e) {
            $output = ['success' => 0, 'msg' => $e->getMessage()];
        }

        return $output;
    }

    /**
     * Fetch available mobile operators.
     */
    public function getOperators($type = 'topups')
    {
        $url = $this->getUrl($type);

        try {
            $this->authenticate($type);

            $response = $this->client->get("{$url}/operators", [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $output = ['success' => 1, 'data' => $data];
        } catch (\Exception $e) {
            $output = ['success' => 0, 'msg' => $e->getMessage()];
        }

        return $output;
    }

    /**
     * Fetch mobile operators by operator Id.
     */
    public function getOperatorByID($operatorId, $type = 'topups')
    {
        $url = $this->getUrl($type);

        try {
            $this->authenticate($type);

            $response = $this->client->get("{$url}/operators/{$operatorId}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $output = ['success' => 1, 'data' => $data];
        } catch (\Exception $e) {
            $output = ['success' => 0, 'msg' => $e->getMessage()];
        }

        return $output;
    }

    /**
     * Fetch mobile operators by ISO Code.
     */
    public function getOperatorByISOCode($isoCode, $type = 'topups')
    {
        $url = $this->getUrl($type);

        try {
            $this->authenticate($type);

            $response = $this->client->get("{$url}/operators/countries/{$isoCode}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $output = ['success' => 1, 'data' => $data];
        } catch (\Exception $e) {
            $output = ['success' => 0, 'msg' => $e->getMessage()];
        }

        return $output;
    }

    /**
     * Send mobile airtime.
     */
    public function sendAirtime($operatorId, $amount, $countryCode, $phoneNumber)
    {
        try {
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

            $data = json_decode($response->getBody(), true);
            $output = ['success' => 1, 'data' => $data];
        } catch (\Exception $e) {
            $output = ['success' => 0, 'msg' => $e->getMessage()];
        }

        return $output;
    }

    /**
     * Get account balance.
     */
    public function getBalance($type = 'topups')
    {
        $url = $this->getUrl($type);
        $this->authenticate($type);

        try {
            $response = $this->client->get("{$url}/accounts/balance", [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $output = ['success' => 1, 'data' => $data];
        } catch (\Exception $e) {
            $output = ['success' => 0, 'msg' => $e->getMessage()];
        }

        return $output;
    }
}
