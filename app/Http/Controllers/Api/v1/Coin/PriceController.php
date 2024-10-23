<?php

namespace App\Http\Controllers\Api\v1\Coin;

use App\Coin;
use App\CoinPrice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coin\ShowPriceRequest;
use App\Services\CoinGeckoService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PriceController extends Controller
{
    protected $coinPriceModel;
    protected $coinGeckoService;

    public function __construct(CoinPrice $coinPrice, CoinGeckoService $coinGeckoService)
    {
        $this->coinPriceModel = $coinPrice;
        $this->coinGeckoService = $coinGeckoService;
    }

    /**
     * Returns the price of a coin.
     *
     * @param \App\Coin $coin
     * @param App\Http\Requests\Coin\ShowPriceRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Coin $coin, ShowPriceRequest $request): JsonResponse
    {
        // if datetime is not present, it'll use now() as default
        $date = new Carbon($request->input('datetime'));

        $coinHistoricalData = $this->coinGeckoService->getHistoricalData($coin->api_coin_id, $date->format('d-m-Y'));

        if (!$coinHistoricalData) {
            return response()->json([
                'error' => 'Unable to fetch data.',
            ]);
        }

        try {
            $this->coinPriceModel->updateOrCreate(
                ['coin_id' => $coin->id, 'price_date' => $date->format('Y-m-d')],
                ['price' => $coinHistoricalData['market_data']['current_price']['usd']]
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return response()->json([
            'currency' => 'USD',
            'price' => $coinHistoricalData['market_data']['current_price']['usd'],
        ]);
    }
}