<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\WeatherForecast;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class DashboardController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                Log::warning('Dashboard accessed without authenticated user');
                return redirect()->route('login')->with('error', 'Please login to access the dashboard.');
            }

            // Get user activities with error handling
            $activities = collect();
            try {
                $activities = Activity::where('user_id', $user->id)
                    ->orderBy('date', 'desc')
                    ->limit(5)
                    ->get();
            } catch (Exception $e) {
                Log::error('Failed to fetch activities for user: ' . $user->id, [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id
                ]);
                // Continue with empty activities collection
            }

            // Get user's default location for display purposes
            $defaultLocation = null;
            $currentWeather = null;
            $weatherSummary = null;
            
            try {
                $locations = $user->locations;
                if ($user->default_location_id) {
                    $defaultLocation = $locations->where('id', $user->default_location_id)->first();
                }
                if (!$defaultLocation) {
                    $defaultLocation = $locations->first();
                }
            } catch (Exception $e) {
                Log::error('Failed to fetch user locations', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id
                ]);
            }

            // Get real-time weather data for the dashboard
            if ($defaultLocation) {
                try {
                    $currentWeather = $this->weatherService->getCurrentWeather(
                        $defaultLocation->latitude,
                        $defaultLocation->longitude
                    );
                } catch (Exception $e) {
                    Log::error('Failed to get current weather for dashboard', [
                        'error' => $e->getMessage(),
                        'location' => $defaultLocation->name,
                        'lat' => $defaultLocation->latitude,
                        'lon' => $defaultLocation->longitude
                    ]);
                }

                // Get weather summary from database
                try {
                    $weatherSummary = $this->getWeatherSummary($user->id, $defaultLocation->name);
                } catch (Exception $e) {
                    Log::error('Failed to get weather summary', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                        'location' => $defaultLocation->name
                    ]);
                }
            }

            return view('dashboard', compact('activities', 'defaultLocation', 'currentWeather', 'weatherSummary'));

        } catch (Exception $e) {
            Log::error('Dashboard error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('dashboard', [
                'activities' => collect(),
                'defaultLocation' => null,
                'currentWeather' => null,
                'weatherSummary' => null
            ])->with('error', 'Unable to load dashboard data. Please try again later.');
        }
    }

    private function getWeatherSummary($userId, $location)
    {
        try {
            // Get weather data from the last 30 days
            $thirtyDaysAgo = now()->subDays(30);
            
            $weatherData = WeatherForecast::where('user_id', $userId)
                ->where('location', $location)
                ->where('date', '>=', $thirtyDaysAgo)
                ->get();

            if ($weatherData->isEmpty()) {
                return null;
            }

            $summary = [
                'total_days' => $weatherData->count(),
                'avg_temperature' => round($weatherData->avg('temp'), 1),
                'max_temperature' => $weatherData->max('temp'),
                'min_temperature' => $weatherData->min('temp'),
                'avg_humidity' => round($weatherData->avg('humidity'), 1),
                'avg_wind_speed' => round($weatherData->avg('wind_speed'), 1),
                'most_common_weather' => $weatherData->groupBy('weather_type')
                    ->map->count()
                    ->sortDesc()
                    ->keys()
                    ->first(),
                'rainy_days' => $weatherData->where('weather_type', 'Rain')->count(),
                'sunny_days' => $weatherData->where('weather_type', 'Clear')->count(),
                'cloudy_days' => $weatherData->where('weather_type', 'Clouds')->count(),
            ];

            return $summary;

        } catch (Exception $e) {
            Log::error('Failed to generate weather summary', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'location' => $location
            ]);
            return null;
        }
    }
}