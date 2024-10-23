<?php

namespace Tests\Unit\Services;

use App\Services\CoinGeckoService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Mockery;

class coinGeckoServiceTest extends TestCase
{
    /** @test */
    public function getHistoricalData_returns_expected_data()
    {
        $apiCoinId = 'bitcoin';
        $date = Carbon::now()->format('d-m-Y');

        $expectedApiResponse = json_decode(file_get_contents(base_path("tests/fixtures/coin_{$apiCoinId}_history.json")), true);

        $mockClient = Mockery::mock(Client::class);
        $mockClient->shouldReceive('request')
            ->once()
            ->with('GET', env('COIN_GECKO_URL') . "coins/{$apiCoinId}/history", [
                'query' => [
                    'date' => $date,
                    'localization' => 'false',
                ]
            ])
            ->andReturn(new Response(200, [], json_encode($expectedApiResponse)));
        $service = new CoinGeckoService($mockClient);

        $actualResponse = $service->getHistoricalData($apiCoinId, $date);

        $this->assertEquals($expectedApiResponse, $actualResponse);
    }

    /** @test */
    public function getHistoricalData_handles_error_gracefully()
    {
        $apiCoinId = 'bitcoin';
        $date = Carbon::now()->format('d-m-Y');

        $mockClient = Mockery::mock(Client::class);
        $mockClient->shouldReceive('request')
            ->once()
            ->with('GET', env('COIN_GECKO_URL') . "coins/{$apiCoinId}/history", [
                'query' => [
                    'date' => $date,
                    'localization' => 'false',
                ]
            ])
            ->andThrow(new RequestException(
                'Error Communicating with Server',
                new Request('GET', env('COIN_GECKO_URL') . "coins/{$apiCoinId}/history")
            ));
        $service = new CoinGeckoService($mockClient);

        $actualResponse = $service->getHistoricalData($apiCoinId, $date);

        $this->assertNull($actualResponse);
        Log::shouldReceive('error')->with('Error Communicating with Server');
    }

    /** @test */
    public function getHistoricalData_handles_timeout_gracefully()
    {
        $apiCoinId = 'bitcoin';
        $date = Carbon::now()->format('d-m-Y');

        $mockClient = Mockery::mock(Client::class);
        $mockClient->shouldReceive('request')
            ->once()
            ->with('GET', env('COIN_GECKO_URL') . "coins/{$apiCoinId}/history", [
                'query' => [
                    'date' => $date,
                    'localization' => 'false',
                ]
            ])
            ->andThrow(new ConnectException(
                'Error Communicating with Server',
                new Request('GET', env('COIN_GECKO_URL') . "coins/{$apiCoinId}/history")
            ));
        $service = new CoinGeckoService($mockClient);

        $actualResponse = $service->getHistoricalData($apiCoinId, $date);

        $this->assertNull($actualResponse);
        Log::shouldReceive('error')->with('Error Communicating with Server');
    }
}
