<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.openweathermap.org/data/2.5/weather';

    public function __construct()
    {
        $this->apiKey = config_value('OPENWEATHER_API_KEY', '');
    }

    /**
     * Get weather data for a city
     *
     * @param string $city
     * @return array
     */
    public function getWeather(string $city): array
    {
        // Return fallback data if no API key
        if (empty($this->apiKey)) {
            return $this->getFallbackData();
        }

        // Try to get from cache (1 hour)
        $cacheKey = 'weather_' . strtolower($city);

        return Cache::remember($cacheKey, 3600, function () use ($city) {
            try {
                $response = Http::timeout(5)->get($this->baseUrl, [
                    'q' => $city,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                    'lang' => 'az'
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    return [
                        'temperature' => round($data['main']['temp']),
                        'humidity' => $data['main']['humidity'],
                        'wind_speed' => round($data['wind']['speed']),
                        'icon' => $this->getWeatherIcon($data['weather'][0]['icon']),
                        'description' => $data['weather'][0]['description'],
                    ];
                }

                Log::warning('Weather API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return $this->getFallbackData();
            } catch (\Exception $e) {
                Log::error('Weather API exception', [
                    'message' => $e->getMessage(),
                ]);

                return $this->getFallbackData();
            }
        });
    }

    /**
     * Get weather icon emoji based on OpenWeatherMap icon code
     *
     * @param string $iconCode
     * @return string
     */
    protected function getWeatherIcon(string $iconCode): string
    {
        $icons = [
            '01d' => '☀️', // clear sky day
            '01n' => '🌙', // clear sky night
            '02d' => '🌤️', // few clouds day
            '02n' => '☁️', // few clouds night
            '03d' => '☁️', // scattered clouds
            '03n' => '☁️',
            '04d' => '☁️', // broken clouds
            '04n' => '☁️',
            '09d' => '🌧️', // shower rain
            '09n' => '🌧️',
            '10d' => '🌦️', // rain day
            '10n' => '🌧️', // rain night
            '11d' => '⛈️', // thunderstorm
            '11n' => '⛈️',
            '13d' => '🌨️', // snow
            '13n' => '🌨️',
            '50d' => '🌫️', // mist
            '50n' => '🌫️',
        ];

        return $icons[$iconCode] ?? '☀️';
    }

    /**
     * Get fallback weather data when API is unavailable
     *
     * @return array
     */
    protected function getFallbackData(): array
    {
        return [
            'temperature' => 24,
            'humidity' => 65,
            'wind_speed' => 12,
            'icon' => '☀️',
            'description' => 'Açıq hava',
        ];
    }
}
