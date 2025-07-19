<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Services\ExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class ReportController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                Log::warning('Reports page accessed without authenticated user');
                return redirect()->route('login')->with('error', 'Please login to view reports.');
            }

            $activities = collect();
            $activityTypes = collect();

            try {
                $activities = $user->activities()
                    ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
                    ->orderBy('date')
                    ->get()
                    ->groupBy(function($item) {
                        return Carbon::parse($item->date)->format('Y-m-d');
                    });

                $activityTypes = $user->activities()
                    ->selectRaw('type, count(*) as count')
                    ->groupBy('type')
                    ->get();

            } catch (Exception $e) {
                Log::error('Failed to fetch report data', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id
                ]);
            }

            return view('reports', compact('activities', 'activityTypes'));

        } catch (Exception $e) {
            Log::error('Reports index error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('reports', [
                'activities' => collect(),
                'activityTypes' => collect()
            ])->with('error', 'Unable to load reports. Please try again later.');
        }
    }

    public function activities()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to view activity reports.');
            }

            $activities = $user->activities()
                ->orderBy('date', 'desc')
                ->paginate(20);

            return view('reports.activities', compact('activities'));

        } catch (Exception $e) {
            Log::error('Activities report error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Unable to load activity reports.');
        }
    }

    public function weather()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to view weather reports.');
            }

            $weatherData = $user->weatherForecasts()
                ->orderBy('date', 'desc')
                ->paginate(20);

            return view('reports.weather', compact('weatherData'));

        } catch (Exception $e) {
            Log::error('Weather report error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Unable to load weather reports.');
        }
    }

    public function generate(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to generate reports.');
            }

            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date|before_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:today',
            ], [
                'start_date.required' => 'Start date is required.',
                'start_date.date' => 'Please enter a valid start date.',
                'start_date.before_or_equal' => 'Start date cannot be in the future.',
                'end_date.required' => 'End date is required.',
                'end_date.date' => 'Please enter a valid end date.',
                'end_date.after_or_equal' => 'End date must be after or equal to start date.',
                'end_date.before_or_equal' => 'End date cannot be in the future.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please correct the errors below.');
            }

            $startDate = $request->start_date;
            $endDate = $request->end_date;

            // Validate date range (max 1 year)
            $startCarbon = Carbon::parse($startDate);
            $endCarbon = Carbon::parse($endDate);
            
            if ($startCarbon->diffInDays($endCarbon) > 365) {
                return redirect()->back()
                    ->withErrors(['end_date' => 'Date range cannot exceed 1 year.'])
                    ->withInput()
                    ->with('error', 'Date range is too large. Please select a smaller range.');
            }

            $activities = collect();
            $activityTypes = collect();

            try {
                $activities = $user->activities()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date')
                    ->get()
                    ->groupBy(function($item) {
                        return Carbon::parse($item->date)->format('Y-m-d');
                    });

                $activityTypes = $user->activities()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->selectRaw('type, count(*) as count')
                    ->groupBy('type')
                    ->get();

                Log::info('Report generated successfully', [
                    'user_id' => $user->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'activities_count' => $activities->flatten()->count(),
                    'types_count' => $activityTypes->count()
                ]);

            } catch (Exception $e) {
                Log::error('Failed to generate report', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to generate report. Please try again.');
            }

            return view('reports', compact('activities', 'activityTypes'))
                ->with('success', 'Report generated successfully for the selected date range.');

        } catch (Exception $e) {
            Log::error('Report generate error', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred while generating the report. Please try again later.');
        }
    }

    public function exportActivities(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to export data.');
            }

            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date|before_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:today',
            ], [
                'start_date.required' => 'Start date is required.',
                'start_date.date' => 'Please enter a valid start date.',
                'start_date.before_or_equal' => 'Start date cannot be in the future.',
                'end_date.required' => 'End date is required.',
                'end_date.date' => 'Please enter a valid end date.',
                'end_date.after_or_equal' => 'End date must be after or equal to start date.',
                'end_date.before_or_equal' => 'End date cannot be in the future.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please correct the errors below.');
            }

            $exportService = new ExportService();
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            return $exportService->exportActivitiesToExcel($startDate, $endDate);

        } catch (Exception $e) {
            Log::error('Export activities error', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to export activities. Please try again.');
        }
    }

    public function exportWeather(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to export data.');
            }

            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date|before_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:today',
            ], [
                'start_date.required' => 'Start date is required.',
                'start_date.date' => 'Please enter a valid start date.',
                'start_date.before_or_equal' => 'Start date cannot be in the future.',
                'end_date.required' => 'End date is required.',
                'end_date.date' => 'Please enter a valid end date.',
                'end_date.after_or_equal' => 'End date must be after or equal to start date.',
                'end_date.before_or_equal' => 'End date cannot be in the future.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please correct the errors below.');
            }

            $exportService = new ExportService();
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            return $exportService->exportWeatherToExcel($startDate, $endDate);

        } catch (Exception $e) {
            Log::error('Export weather error', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to export weather data. Please try again.');
        }
    }

    public function exportSummary(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to export data.');
            }

            $validator = Validator::make($request->all(), [
                'start_date' => 'nullable|date|before_or_equal:today',
                'end_date' => 'nullable|date|after_or_equal:start_date|before_or_equal:today',
            ], [
                'start_date.date' => 'Please enter a valid start date.',
                'start_date.before_or_equal' => 'Start date cannot be in the future.',
                'end_date.date' => 'Please enter a valid end date.',
                'end_date.after_or_equal' => 'End date must be after or equal to start date.',
                'end_date.before_or_equal' => 'End date cannot be in the future.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please correct the errors below.');
            }

            $exportService = new ExportService();
            $summary = $exportService->generateSummaryReport($request->start_date, $request->end_date);

            return response()->json($summary);

        } catch (Exception $e) {
            Log::error('Export summary error', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to generate summary. Please try again.');
        }
    }
}