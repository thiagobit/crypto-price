<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class CoinGeckoService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getHistoricalData(string $apiCoinId, string $date): ?array
    {
        try {
            $response = $this->client->request('GET', env('COIN_GECKO_URL') . "coins/{$apiCoinId}/history", [
                'query' => [
                    'date' => $date,
                    'localization' => 'false',
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            Log::error($e->getMessage());

            return null;
        }
    }
}