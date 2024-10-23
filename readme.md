# Crypto Price API

This application consists in get the price history in USD of a given crypto coin using [CoinGeckoAPI](https://docs.coingecko.com/reference/introduction).
* It works with BTC, BCH, LTC, ETH, DACXI, LINK, USDT, XLM, DOT, ADA, SOL, AVAX, LUNC, MATIC, USDC, BNB, XRP, UNI, MKR, BAT, SAND, and EOS.
* There is a _docker-composer.yml_ file to facilitate the environment setup with _Docker_.
* It's using _Laravel 5.6_ with _PHP-FPM 7.1_, _MySQL 5.7_, _Nginx_ and _Composer 2.2_ to install dependencies.
* The webserver is set to use port _8080_.

## Requirements
- [Docker](https://docs.docker.com/engine/install/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Configuration:
1. Create the `.env` file based on `.env.example`:
```shell
cp .env.example .env
```

2. In the `.env`, set your _CoinGeckoAPI_ credentials.
```shell
COIN_GECKO_URL="https://api.coingecko.com/api/v3/"
COIN_GECKO_API_KEY_FIELD="x-cg-api-key"
COIN_GECKO_API_KEY=""
```

3. Create and run _Docker_ containers:
```shell
docker-compose up --build -d
```

4. Generate the _application key_:
```shell
docker-compose exec app artisan key:generate
```

5. Run the migrations:
```shell
docker-compose exec app artisan migrate
```

6. Seed the coins database:
```shell
docker-compose exec app artisan db:seed
```

7. To run the tests routines _(optional)_:
```shell
docker-compose exec app php vendor/bin/phpunit
```

## Endpoints:

### GET api/v1/coin/{coin}/price
- Description: Get the price history of a given crypto coin.
- Parameters:
  - `{coin}`:
    - Description: The crypto coin.
    - Type: `string`
    - Required: `true`
    - Possible values: `BTC`, `BCH`, `LTC`, `ETH`, `DACXI`, `LINK`, `USDT`, `XLM`, `DOT`, `ADA`, `SOL`, `AVAX`, `LUNC`, `MATIC`, `USDC`, `BNB`, `XRP`, `UNI`, `MKR`, `BAT`, `SAND`, `EOS`.
  - `datetime`:
    - Description: The UTC datetime. If it's not set, it'll consider the current day.
    - Type: `string`
    - Required: `false`

- Example: `GET http://localhost:8080/api/v1/coin/BTC/price/?datetime=2024-10-21T14:30:00Z`
  - Output:
    ```json
    {
        "currency": "USD",
        "price": 68962.82918045693
    }
    ```