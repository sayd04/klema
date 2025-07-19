<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\WeatherForecast;
use App\Models\Location;
use App\Exports\ActivitiesExport;
use App\Exports\WeatherExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Exception;

class ExportService
{
    public function exportActivitiesToExcel($startDate = null, $endDate = null)
    {
        try {
            $user = Auth::user();
            
            $query = Activity::where('user_id', $user->id);
            
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
            
            $activities = $query->orderBy('date', 'desc')->get();
            
            $filename = 'activities_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            $filepath = storage_path('app/public/exports/' . $filename);
            
            // Create exports directory if it doesn't exist
            if (!file_exists(dirname($filepath))) {
                mkdir(dirname($filepath), 0755, true);
            }
            
            $file = fopen($filepath, 'w');
            
            // Add headers
            fputcsv($file, [
                'ID',
                'Title',
                'Description',
                'Type',
                'Date',
                'Created At',
                'Updated At'
            ]);
            
            // Add data
            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->title,
                    $activity->description,
                    ucfirst($activity->type),
                    Carbon::parse($activity->date)->format('Y-m-d'),
                    Carbon::parse($activity->created_at)->format('Y-m-d H:i:s'),
                    Carbon::parse($activity->updated_at)->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
            
            return response()->download($filepath)->deleteFileAfterSend();
            
        } catch (Exception $e) {
            throw new Exception('Failed to export activities to Excel: ' . $e->getMessage());
        }
    }

    public function exportWeatherToExcel($startDate = null, $endDate = null)
    {
        try {
            $user = Auth::user();
            
            $query = WeatherForecast::whereHas('location', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
            
            $weatherData = $query->with('location')->orderBy('date', 'desc')->get();
            
            $filename = 'weather_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            $filepath = storage_path('app/public/exports/' . $filename);
            
            // Create exports directory if it doesn't exist
            if (!file_exists(dirname($filepath))) {
                mkdir(dirname($filepath), 0755, true);
            }
            
            $file = fopen($filepath, 'w');
            
            // Add headers
            fputcsv($file, [
                'ID',
                'Location',
                'Date',
                'Temperature (°C)',
                'Humidity (%)',
                'Weather Type',
                'Weather Icon',
                'Advice',
                'Created At'
            ]);
            
            // Add data
            foreach ($weatherData as $weather) {
                fputcsv($file, [
                    $weather->id,
                    $weather->location->name ?? 'N/A',
                    Carbon::parse($weather->date)->format('Y-m-d'),
                    $weather->temp,
                    $weather->humidity,
                    $weather->weather_type,
                    $weather->weather_icon,
                    $weather->advice,
                    Carbon::parse($weather->created_at)->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
            
            return response()->download($filepath)->deleteFileAfterSend();
            
        } catch (Exception $e) {
            throw new Exception('Failed to export weather data to Excel: ' . $e->getMessage());
        }
    }

    public function exportActivitiesToCSV($startDate = null, $endDate = null)
    {
        try {
            $user = Auth::user();
            
            $query = Activity::where('user_id', $user->id);
            
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
            
            $activities = $query->orderBy('date', 'desc')->get();
            
            $filename = 'activities_' . now()->format('Y-m-d_H-i-s') . '.csv';
            $filepath = storage_path('app/public/exports/' . $filename);
            
            // Create exports directory if it doesn't exist
            if (!file_exists(dirname($filepath))) {
                mkdir(dirname($filepath), 0755, true);
            }
            
            $file = fopen($filepath, 'w');
            
            // Add headers
            fputcsv($file, [
                'ID',
                'Title',
                'Description',
                'Type',
                'Date',
                'Created At',
                'Updated At'
            ]);
            
            // Add data
            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->title,
                    $activity->description,
                    ucfirst($activity->type),
                    Carbon::parse($activity->date)->format('Y-m-d'),
                    Carbon::parse($activity->created_at)->format('Y-m-d H:i:s'),
                    Carbon::parse($activity->updated_at)->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
            
            return response()->download($filepath)->deleteFileAfterSend();
            
        } catch (Exception $e) {
            throw new Exception('Failed to export activities to CSV: ' . $e->getMessage());
        }
    }

    public function exportWeatherToCSV($startDate = null, $endDate = null)
    {
        try {
            $user = Auth::user();
            
            $query = WeatherForecast::whereHas('location', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
            
            $weatherData = $query->with('location')->orderBy('date', 'desc')->get();
            
            $filename = 'weather_' . now()->format('Y-m-d_H-i-s') . '.csv';
            $filepath = storage_path('app/public/exports/' . $filename);
            
            // Create exports directory if it doesn't exist
            if (!file_exists(dirname($filepath))) {
                mkdir(dirname($filepath), 0755, true);
            }
            
            $file = fopen($filepath, 'w');
            
            // Add headers
            fputcsv($file, [
                'ID',
                'Location',
                'Date',
                'Temperature (°C)',
                'Humidity (%)',
                'Weather Type',
                'Weather Icon',
                'Advice',
                'Created At'
            ]);
            
            // Add data
            foreach ($weatherData as $weather) {
                fputcsv($file, [
                    $weather->id,
                    $weather->location->name ?? 'N/A',
                    Carbon::parse($weather->date)->format('Y-m-d'),
                    $weather->temp,
                    $weather->humidity,
                    $weather->weather_type,
                    $weather->weather_icon,
                    $weather->advice,
                    Carbon::parse($weather->created_at)->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
            
            return response()->download($filepath)->deleteFileAfterSend();
            
        } catch (Exception $e) {
            throw new Exception('Failed to export weather data to CSV: ' . $e->getMessage());
        }
    }

    public function generateSummaryReport($startDate = null, $endDate = null)
    {
        try {
            $user = Auth::user();
            
            // Get activities summary
            $activitiesQuery = Activity::where('user_id', $user->id);
            if ($startDate && $endDate) {
                $activitiesQuery->whereBetween('date', [$startDate, $endDate]);
            }
            
            $activities = $activitiesQuery->get();
            $activityTypes = $activities->groupBy('type')->map->count();
            
            // Get weather summary
            $weatherQuery = WeatherForecast::whereHas('location', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            
            if ($startDate && $endDate) {
                $weatherQuery->whereBetween('date', [$startDate, $endDate]);
            }
            
            $weatherData = $weatherQuery->get();
            
            $summary = [
                'total_activities' => $activities->count(),
                'activity_types' => $activityTypes,
                'total_weather_records' => $weatherData->count(),
                'avg_temperature' => $weatherData->avg('temp'),
                'avg_humidity' => $weatherData->avg('humidity'),
                'date_range' => [
                    'start' => $startDate ? Carbon::parse($startDate)->format('Y-m-d') : 'All time',
                    'end' => $endDate ? Carbon::parse($endDate)->format('Y-m-d') : 'All time'
                ]
            ];
            
            return $summary;
            
        } catch (Exception $e) {
            throw new Exception('Failed to generate summary report: ' . $e->getMessage());
        }
    }
} 