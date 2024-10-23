<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoinPrice extends Model
{
    protected $table = 'coins_prices';

    protected $fillable = ['coin_id', 'price', 'price_date'];

    public function coin()
    {
        return $this->belongsTo('App\Coin');
    }
}
