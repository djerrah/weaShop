<?php

namespace App\Spi;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherSpi
{
    public function __construct(
        private readonly string              $weatherApiUrl,
        private readonly string              $weatherApiKey,
        private readonly HttpClientInterface $client,
    )
    {
    }

    public function getWeather(string $city): array
    {
        $response = $this->client->request(
            'GET',
            "{$this->weatherApiUrl}?key={$this->weatherApiKey}&q={$city}&days=1&aqi=no&alerts=no"
        );

        $arrayResponse = $response->toArray();

        return [
            'city' => $city,
            'is' => (int)$arrayResponse['current']['temp_c'] > 15 ? 'hot' : 'cold',
            'date' => 'today',
        ];
    }
}