<?php

use App\Coin;
use Illuminate\Database\Seeder;

class CoinsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Coin::insert([
            ['api_coin_id' => 'cardano', 'symbol' => 'ada', 'name' => 'Cardano'],
            ['api_coin_id' => 'avalanche-2', 'symbol' => 'avax', 'name' => 'Avalanche'],
            ['api_coin_id' => 'batic', 'symbol' => 'bat', 'name' => 'Batic'],
            ['api_coin_id' => 'bitcoin-cash', 'symbol' => 'bch', 'name' => 'Bitcoin Cash'],
            ['api_coin_id' => 'binancecoin', 'symbol' => 'bnb', 'name' => 'BNB'],
            ['api_coin_id' => 'bitcoin', 'symbol' => 'btc', 'name' => 'Bitcoin'],
            ['api_coin_id' => 'dacxi', 'symbol' => 'dacxi', 'name' => 'Dacxi'],
            ['api_coin_id' => 'xcdot', 'symbol' => 'dot', 'name' => 'xcDOT'],
            ['api_coin_id' => 'eos', 'symbol' => 'eos', 'name' => 'EOS'],
            ['api_coin_id' => 'ethereum', 'symbol' => 'eth', 'name' => 'Ethereum'],
            ['api_coin_id' => 'chainlink', 'symbol' => 'link', 'name' => 'Chainlink'],
            ['api_coin_id' => 'litecoin', 'symbol' => 'ltc', 'name' => 'Litecoin'],
            ['api_coin_id' => 'terra-luna', 'symbol' => 'lunc', 'name' => 'Terra Luna Classic'],
            ['api_coin_id' => 'matic-network', 'symbol' => 'matic', 'name' => 'Polygon'],
            ['api_coin_id' => 'maker', 'symbol' => 'mkr', 'name' => 'Maker'],
            ['api_coin_id' => 'the-sandbox', 'symbol' => 'sand', 'name' => 'The Sandbox'],
            ['api_coin_id' => 'solana', 'symbol' => 'sol', 'name' => 'Solana'],
            ['api_coin_id' => 'uni', 'symbol' => 'uni', 'name' => 'Uni'],
            ['api_coin_id' => 'usd-coin', 'symbol' => 'usdc', 'name' => 'USDC'],
            ['api_coin_id' => 'tether', 'symbol' => 'usdt', 'name' => 'Tether'],
            ['api_coin_id' => 'stellar', 'symbol' => 'xlm', 'name' => 'Stellar'],
            ['api_coin_id' => 'ripple', 'symbol' => 'xrp', 'name' => 'XR']
        ]);
    }
}
