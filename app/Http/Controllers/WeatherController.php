<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\WeatherForecast;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class WeatherController extends Controller
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
                Log::warning('Weather page accessed without authenticated user');
                return redirect()->route('login')->with('error', 'Please login to access weather information.');
            }

            $locations = collect();
            $selectedLocation = null;
            $weather = null;
            $forecast = null;
            $weatherSummary = null;

            try {
                $locations = $user->locations;
                // Use default_location_id if set, otherwise first location
                if ($user->default_location_id) {
                    $selectedLocation = $locations->where('id', $user->default_location_id)->first();
                }
                if (!$selectedLocation) {
                    $selectedLocation = $locations->first();
                }
            } catch (Exception $e) {
                Log::error('Failed to fetch user locations', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id
                ]);
            }

            if ($selectedLocation) {
                try {
                    $weather = $this->weatherService->getCurrentWeather(
                        $selectedLocation->latitude,
                        $selectedLocation->longitude
                    );
                } catch (Exception $e) {
                    Log::error('Failed to get current weather', [
                        'error' => $e->getMessage(),
                        'location' => $selectedLocation->name,
                        'lat' => $selectedLocation->latitude,
                        'lon' => $selectedLocation->longitude
                    ]);
                    $weather = null;
                }

                try {
                    $forecast = $this->weatherService->getForecast(
                        $selectedLocation->latitude,
                        $selectedLocation->longitude
                    );
                } catch (Exception $e) {
                    Log::error('Failed to get weather forecast', [
                        'error' => $e->getMessage(),
                        'location' => $selectedLocation->name,
                        'lat' => $selectedLocation->latitude,
                        'lon' => $selectedLocation->longitude
                    ]);
                    $forecast = null;
                }

                try {
                    $this->weatherService->saveForecastToDatabase($forecast, $selectedLocation->name ?? null);
                } catch (Exception $e) {
                    Log::error('Failed to save forecast to database', [
                        'error' => $e->getMessage(),
                        'location' => $selectedLocation->name
                    ]);
                    // Continue without saving to database
                }

                // Get weather summary
                try {
                    $weatherSummary = $this->getWeatherSummary($user->id, $selectedLocation->name);
                } catch (Exception $e) {
                    Log::error('Failed to get weather summary', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                        'location' => $selectedLocation->name
                    ]);
                    $weatherSummary = null;
                }
            }

            return view('weather', compact('locations', 'selectedLocation', 'weather', 'forecast', 'weatherSummary'));

        } catch (Exception $e) {
            Log::error('Weather page error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('weather', [
                'locations' => collect(),
                'selectedLocation' => null,
                'weather' => null,
                'forecast' => null,
                'weatherSummary' => null
            ])->with('error', 'Unable to load weather data. Please try again later.');
        }
    }

    public function getWeather(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'location_id' => 'required|exists:locations,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid location provided.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $location = Location::find($request->location_id);
            
            if (!$location) {
                return response()->json([
                    'success' => false,
                    'message' => 'Location not found.'
                ], 404);
            }

            $weather = null;
            $forecast = null;

            try {
                $weather = $this->weatherService->getCurrentWeather(
                    $location->latitude,
                    $location->longitude
                );
            } catch (Exception $e) {
                Log::error('Failed to get current weather for location', [
                    'error' => $e->getMessage(),
                    'location_id' => $location->id,
                    'location_name' => $location->name
                ]);
            }
            
            try {
                $forecast = $this->weatherService->getForecast(
                    $location->latitude,
                    $location->longitude
                );
            } catch (Exception $e) {
                Log::error('Failed to get forecast for location', [
                    'error' => $e->getMessage(),
                    'location_id' => $location->id,
                    'location_name' => $location->name
                ]);
            }

            return response()->json([
                'success' => true,
                'weather' => $weather,
                'forecast' => $forecast,
            ]);

        } catch (Exception $e) {
            Log::error('Get weather API error', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch weather data. Please try again later.'
            ], 500);
        }
    }

    public function getCurrentWeather($locationId)
    {
        try {
            $location = Location::find($locationId);
            
            if (!$location) {
                return response()->json([
                    'success' => false,
                    'message' => 'Location not found.'
                ], 404);
            }

            $weather = $this->weatherService->getCurrentWeather(
                $location->latitude,
                $location->longitude
            );

            return response()->json([
                'success' => true,
                'weather' => $weather,
                'location' => $location->name,
                'timestamp' => now()->toISOString()
            ]);

        } catch (Exception $e) {
            Log::error('Get current weather API error', [
                'error' => $e->getMessage(),
                'location_id' => $locationId,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch current weather data.'
            ], 500);
        }
    }

    public function getWeatherForecast($locationId)
    {
        try {
            $location = Location::find($locationId);
            
            if (!$location) {
                return response()->json([
                    'success' => false,
                    'message' => 'Location not found.'
                ], 404);
            }

            $forecast = $this->weatherService->getForecast(
                $location->latitude,
                $location->longitude,
                7 // 7 days for dashboard
            );

            return response()->json([
                'success' => true,
                'forecast' => $forecast,
                'location' => $location->name,
                'timestamp' => now()->toISOString()
            ]);

        } catch (Exception $e) {
            Log::error('Get weather forecast API error', [
                'error' => $e->getMessage(),
                'location_id' => $locationId,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch forecast data.'
            ], 500);
        }
    }

    public function getDashboardWeather()
    {
        try {
            $user = Auth::user();
            $defaultLocation = $user->locations()->first();

            if (!$defaultLocation) {
                return response()->json([
                    'success' => false,
                    'message' => 'No location set.'
                ], 404);
            }

            $weather = $this->weatherService->getCurrentWeather(
                $defaultLocation->latitude,
                $defaultLocation->longitude
            );

            $forecast = $this->weatherService->getForecast(
                $defaultLocation->latitude,
                $defaultLocation->longitude,
                7
            );

            // Get today's forecast from database
            $today = now()->toDateString();
            $todayForecast = WeatherForecast::where('user_id', $user->id)
                ->where('date', $today)
                ->where('location', $defaultLocation->name)
                ->first();

            return response()->json([
                'success' => true,
                'weather' => $weather,
                'forecast' => $forecast,
                'todayForecast' => $todayForecast,
                'location' => $defaultLocation->name,
                'timestamp' => now()->toISOString()
            ]);

        } catch (Exception $e) {
            Log::error('Get dashboard weather API error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch dashboard weather data.'
            ], 500);
        }
    }

    public function addLocation(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please correct the errors below.');
            }

            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to add locations.');
            }

            $data = [
                'name' => $request->name,
            ];

            if ($request->filled('latitude')) {
                $data['latitude'] = $request->latitude;
            }
            if ($request->filled('longitude')) {
                $data['longitude'] = $request->longitude;
            }

            try {
                $user->locations()->create($data);
                Log::info('Location added successfully', [
                    'user_id' => $user->id,
                    'location_name' => $request->name
                ]);

                return redirect()->route('weather')->with('success', 'Location added successfully');
            } catch (Exception $e) {
                Log::error('Failed to add location', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                    'location_data' => $data
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to add location. Please try again.');
            }

        } catch (Exception $e) {
            Log::error('Add location error', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }

    public function setDefaultLocation(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
        ]);
        $user = Auth::user();
        $user->default_location_id = $request->location_id;
        $user->save();
        return response()->json(['success' => true]);
    }

    private function getWeatherSummary($userId, $location)
    {
        try {
            // Get weather data from the last 30 days
            $thirtyDaysAgo = now()->subDays(30);
            
            $weatherData = \App\Models\WeatherForecast::where('user_id', $userId)
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