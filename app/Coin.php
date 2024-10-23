<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    protected $fillable = ['api_coin_id', 'symbol', 'name'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'symbol';
    }

    public function prices()
    {
        return $this->hasMany('App\CoinPrice');
    }
}
