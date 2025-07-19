<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\WeatherForecast;
use Illuminate\Support\Facades\Auth;
use Exception;

class WeatherService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.openweathermap.org/data/2.5/',
            'timeout' => 10,
            'connect_timeout' => 5,
        ]);
        $this->apiKey = config('services.openweather.key');
        
        if (!$this->apiKey) {
            Log::error('OpenWeather API key not configured');
            throw new Exception('Weather service is not properly configured.');
        }
    }

    public function getCurrentWeather($lat, $lon)
    {
        try {
            // Validate coordinates
            if (!$this->validateCoordinates($lat, $lon)) {
                Log::error('Invalid coordinates provided for weather request', [
                    'lat' => $lat,
                    'lon' => $lon
                ]);
                throw new Exception('Invalid coordinates provided.');
            }

            $cacheKey = "weather_{$lat}_{$lon}";

            return Cache::remember($cacheKey, now()->addHour(), function () use ($lat, $lon) {
                try {
                    $response = $this->client->get('weather', [
                        'query' => [
                            'lat' => $lat,
                            'lon' => $lon,
                            'appid' => $this->apiKey,
                            'units' => 'metric',
                        ]
                    ]);

                    $data = json_decode($response->getBody()->getContents(), true);
                    
                    if (!$data || !isset($data['main'])) {
                        Log::error('Invalid weather data received from API', [
                            'lat' => $lat,
                            'lon' => $lon,
                            'response' => $data
                        ]);
                        throw new Exception('Invalid weather data received.');
                    }

                    Log::info('Weather data retrieved successfully', [
                        'lat' => $lat,
                        'lon' => $lon,
                        'temp' => $data['main']['temp'] ?? null
                    ]);

                    return $data;

                } catch (RequestException $e) {
                    Log::error('Weather API request failed', [
                        'error' => $e->getMessage(),
                        'lat' => $lat,
                        'lon' => $lon,
                        'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null
                    ]);
                    throw new Exception('Unable to fetch weather data. Please try again later.');
                } catch (ConnectException $e) {
                    Log::error('Weather API connection failed', [
                        'error' => $e->getMessage(),
                        'lat' => $lat,
                        'lon' => $lon
                    ]);
                    throw new Exception('Unable to connect to weather service. Please check your internet connection.');
                } catch (Exception $e) {
                    Log::error('Weather API unexpected error', [
                        'error' => $e->getMessage(),
                        'lat' => $lat,
                        'lon' => $lon
                    ]);
                    throw new Exception('Weather service temporarily unavailable. Please try again later.');
                }
            });

        } catch (Exception $e) {
            Log::error('Get current weather error', [
                'error' => $e->getMessage(),
                'lat' => $lat,
                'lon' => $lon
            ]);
            throw $e;
        }
    }

    public function getForecast($lat, $lon, $days = 5)
    {
        try {
            // Validate coordinates
            if (!$this->validateCoordinates($lat, $lon)) {
                Log::error('Invalid coordinates provided for forecast request', [
                    'lat' => $lat,
                    'lon' => $lon
                ]);
                throw new Exception('Invalid coordinates provided.');
            }

            // Validate days parameter
            if ($days < 1 || $days > 7) {
                $days = 5; // Default to 5 days if invalid
            }

            $cacheKey = "forecast_{$lat}_{$lon}_{$days}";

            return Cache::remember($cacheKey, now()->addHour(), function () use ($lat, $lon, $days) {
                try {
                    $response = $this->client->get('forecast', [
                        'query' => [
                            'lat' => $lat,
                            'lon' => $lon,
                            'appid' => $this->apiKey,
                            'units' => 'metric',
                            'cnt' => $days * 8, // OpenWeather API returns 3-hour intervals, so 8 per day
                        ]
                    ]);

                    $data = json_decode($response->getBody()->getContents(), true);
                    
                    if (!$data || !isset($data['list']) || !is_array($data['list'])) {
                        Log::error('Invalid forecast data received from API', [
                            'lat' => $lat,
                            'lon' => $lon,
                            'response' => $data
                        ]);
                        throw new Exception('Invalid forecast data received.');
                    }

                    // Filter to get daily forecasts (every 24 hours)
                    $dailyForecasts = [];
                    $processedDates = [];
                    
                    foreach ($data['list'] as $forecast) {
                        $date = date('Y-m-d', $forecast['dt']);
                        
                        if (!in_array($date, $processedDates) && count($dailyForecasts) < $days) {
                            $dailyForecasts[] = $forecast;
                            $processedDates[] = $date;
                        }
                    }

                    // Update the data with filtered daily forecasts
                    $data['list'] = $dailyForecasts;

                    Log::info('Forecast data retrieved successfully', [
                        'lat' => $lat,
                        'lon' => $lon,
                        'days' => $days,
                        'forecast_count' => count($dailyForecasts)
                    ]);

                    return $data;

                } catch (RequestException $e) {
                    Log::error('Forecast API request failed', [
                        'error' => $e->getMessage(),
                        'lat' => $lat,
                        'lon' => $lon,
                        'days' => $days,
                        'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null
                    ]);
                    throw new Exception('Unable to fetch forecast data. Please try again later.');
                } catch (ConnectException $e) {
                    Log::error('Forecast API connection failed', [
                        'error' => $e->getMessage(),
                        'lat' => $lat,
                        'lon' => $lon,
                        'days' => $days
                    ]);
                    throw new Exception('Unable to connect to forecast service. Please check your internet connection.');
                } catch (Exception $e) {
                    Log::error('Forecast API unexpected error', [
                        'error' => $e->getMessage(),
                        'lat' => $lat,
                        'lon' => $lon,
                        'days' => $days
                    ]);
                    throw new Exception('Forecast service temporarily unavailable. Please try again later.');
                }
            });

        } catch (Exception $e) {
            Log::error('Get forecast error', [
                'error' => $e->getMessage(),
                'lat' => $lat,
                'lon' => $lon,
                'days' => $days
            ]);
            throw $e;
        }
    }

    public function saveForecastToDatabase($forecast, $location = null)
    {
        try {
            if (!$forecast || !isset($forecast['list']) || !is_array($forecast['list'])) {
                Log::warning('Invalid forecast data provided for database save', [
                    'forecast' => $forecast,
                    'location' => $location
                ]);
                return;
            }

            $user = Auth::user();
            if (!$user) {
                Log::warning('No authenticated user for forecast save', [
                    'location' => $location
                ]);
                return;
            }

            foreach ($forecast['list'] as $day) {
                try {
                    if (!isset($day['dt']) || !isset($day['main']) || !isset($day['weather'][0])) {
                        Log::warning('Invalid day data in forecast', [
                            'day' => $day
                        ]);
                        continue;
                    }

                    $date = \Carbon\Carbon::createFromTimestamp($day['dt'])->toDateString();
                    $advice = $this->generateAdvice($day);
                    
                    WeatherForecast::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'date' => $date,
                            'location' => $location,
                        ],
                        [
                            'temp' => $day['main']['temp'] ?? null,
                            'weather_type' => $day['weather'][0]['main'] ?? null,
                            'weather_icon' => $day['weather'][0]['icon'] ?? null,
                            'humidity' => $day['main']['humidity'] ?? null,
                            'wind_speed' => $day['wind']['speed'] ?? null,
                            'advice' => $advice,
                        ]
                    );

                } catch (Exception $e) {
                    Log::error('Failed to save forecast day to database', [
                        'error' => $e->getMessage(),
                        'day' => $day,
                        'user_id' => $user->id
                    ]);
                    // Continue with next day
                }
            }

            Log::info('Forecast saved to database successfully', [
                'user_id' => $user->id,
                'location' => $location,
                'days_count' => count($forecast['list'])
            ]);

        } catch (Exception $e) {
            Log::error('Save forecast to database error', [
                'error' => $e->getMessage(),
                'location' => $location,
                'user_id' => Auth::id()
            ]);
            throw new Exception('Failed to save forecast data.');
        }
    }

    private function generateAdvice($day)
    {
        try {
            if (!isset($day['weather'][0]['main'])) {
                return 'Check the weather before planning your activities.';
            }

            $weather = strtolower($day['weather'][0]['main']);
            
            if (str_contains($weather, 'rain')) {
                return 'It might rain today. Consider indoor tasks or avoid watering.';
            } elseif (str_contains($weather, 'clear')) {
                return 'Great day for planting, harvesting, or outdoor work!';
            } elseif (str_contains($weather, 'cloud')) {
                return 'Mild weather, good for most activities.';
            } elseif (str_contains($weather, 'snow')) {
                return 'Snow expected. Protect sensitive plants and avoid field work.';
            } elseif (str_contains($weather, 'storm')) {
                return 'Stormy weather. Stay safe and avoid outdoor activities.';
            }
            
            return 'Check the weather before planning your activities.';

        } catch (Exception $e) {
            Log::error('Failed to generate weather advice', [
                'error' => $e->getMessage(),
                'day' => $day
            ]);
            return 'Check the weather before planning your activities.';
        }
    }

    private function validateCoordinates($lat, $lon)
    {
        return is_numeric($lat) && is_numeric($lon) &&
               $lat >= -90 && $lat <= 90 &&
               $lon >= -180 && $lon <= 180;
    }
}