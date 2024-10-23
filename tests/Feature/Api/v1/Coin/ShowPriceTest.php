<?php

namespace Tests\Feature\Api\v1\Coin;

use App\Coin;
use App\CoinPrice;
use App\Services\CoinGeckoService;
use Carbon\Carbon;
use DatabaseSeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery;

class ShowPriceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        (new DatabaseSeeder)->run();
    }

    /** @test */
    public function tests_are_using_sqlite_as_db()
    {
        $this->assertEquals('sqlite', DB::connection()->getDriverName());
    }

    /** @test */
    public function it_shows_coin_price_and_store_it_in_db()
    {
        $apiCoinId = 'bitcoin';
        $dateTimeUTC = '2024-10-21T14:30:00Z';
        $bitcoinHistoryFixture = json_decode(file_get_contents(base_path("tests/fixtures/coin_{$apiCoinId}_history.json")), true);
        $date = new Carbon($dateTimeUTC);

        $mockClient = Mockery::mock(CoinGeckoService::class);
        $mockClient->shouldReceive('getHistoricalData')
            ->once()
            ->with($apiCoinId, $date->format('d-m-Y'))
            ->andReturn($bitcoinHistoryFixture);

        $this->app->instance(CoinGeckoService::class, $mockClient);

        $coin = Coin::where('api_coin_id', $apiCoinId)->first();

        $response = $this->get(route('api.v1.coin.price.show', ['coin' => $coin->symbol, 'datetime' => $dateTimeUTC]));
        $response->assertStatus(200);
        $response->assertJson([
            'currency' => 'USD',
            'price' => $bitcoinHistoryFixture['market_data']['current_price']['usd']
        ]);
        $this->assertDatabaseHas('coins_prices', [
            'coin_id' => $coin->id,
            'price' => $bitcoinHistoryFixture['market_data']['current_price']['usd'],
            'price_date' => $date->format('Y-m-d'),
        ]);
    }

    /** @test */
    public function it_uses_today_as_default_datetime()
    {
        $apiCoinId = 'bitcoin';
        $dateTimeUTC = null;
        $bitcoinHistoryFixture = json_decode(file_get_contents(base_path("tests/fixtures/coin_{$apiCoinId}_history.json")), true);
        $date = new Carbon($dateTimeUTC); // today

        $mockClient = Mockery::mock(CoinGeckoService::class);
        $mockClient->shouldReceive('getHistoricalData')
            ->once()
            ->with($apiCoinId, $date->format('d-m-Y'))
            ->andReturn($bitcoinHistoryFixture);

        $this->app->instance(CoinGeckoService::class, $mockClient);

        $coin = Coin::where('api_coin_id', $apiCoinId)->first();

        $response = $this->get(route('api.v1.coin.price.show', ['coin' => $coin->symbol, 'datetime' => $dateTimeUTC]));
        $response->assertStatus(200);
        $response->assertJson([
            'currency' => 'USD',
            'price' => $bitcoinHistoryFixture['market_data']['current_price']['usd'],
        ]);
        $this->assertDatabaseHas('coins_prices', [
            'coin_id' => $coin->id,
            'price' => $bitcoinHistoryFixture['market_data']['current_price']['usd'],
            'price_date' => $date->format('Y-m-d'),
        ]);
    }

    /** @test */
    public function it_handles_coingecko_api_error_gracefully()
    {
        $apiCoinId = 'bitcoin';
        $dateTimeUTC = '2024-10-21T14:30:00Z';
        $date = new Carbon($dateTimeUTC); // today

        $mockClient = Mockery::mock(CoinGeckoService::class);
        $mockClient->shouldReceive('getHistoricalData')
            ->once()
            ->with($apiCoinId, $date->format('d-m-Y'))
            ->andReturn(null);

        $this->app->instance(CoinGeckoService::class, $mockClient);

        $coin = Coin::where('api_coin_id', $apiCoinId)->first();

        $response = $this->get(route('api.v1.coin.price.show', ['coin' => $coin->symbol, 'datetime' => $dateTimeUTC]));
        $response->assertJson(['error' => 'Unable to fetch data.']);
    }

    /** @test */
    public function it_handles_db_error_gracefully()
    {
        $apiCoinId = 'bitcoin';
        $dateTimeUTC = '2024-10-21T14:30:00Z';
        $bitcoinHistoryFixture = json_decode(file_get_contents(base_path("tests/fixtures/coin_{$apiCoinId}_history.json")), true);
        $date = new Carbon($dateTimeUTC); // today

        $coin = Coin::where('api_coin_id', $apiCoinId)->first();

        $mockClient = Mockery::mock(CoinGeckoService::class);
        $mockClient->shouldReceive('getHistoricalData')
            ->once()
            ->with($apiCoinId, $date->format('d-m-Y'))
            ->andReturn($bitcoinHistoryFixture);

        $this->app->instance(CoinGeckoService::class, $mockClient);

        $mockModel = Mockery::mock(CoinPrice::class);
        $mockModel->shouldReceive('updateOrCreate')
            ->once()
            ->andThrow(new \Exception('Database error'));

        $this->app->instance(CoinPrice::class, $mockModel);

        $response = $this->get(route('api.v1.coin.price.show', ['coin' => $coin->symbol, 'datetime' => $dateTimeUTC]));
        $response->assertStatus(200);
        Log::shouldReceive('error')->with('Database error');
    }
}
