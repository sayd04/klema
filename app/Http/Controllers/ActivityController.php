<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class ActivityController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                Log::warning('Activities page accessed without authenticated user');
                return redirect()->route('login')->with('error', 'Please login to view activities.');
            }

            $activities = collect();
            try {
                $activities = $user->activities()->orderBy('date', 'desc')->get();
            } catch (Exception $e) {
                Log::error('Failed to fetch activities', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id
                ]);
            }

            return view('activities.index', compact('activities'));

        } catch (Exception $e) {
            Log::error('Activities index error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('activities.index', ['activities' => collect()])
                ->with('error', 'Unable to load activities. Please try again later.');
        }
    }

    public function create()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to create activities.');
            }

            return view('activities.create');

        } catch (Exception $e) {
            Log::error('Activity create page error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return redirect()->route('activities.index')
                ->with('error', 'Unable to load create page. Please try again later.');
        }
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to create activities.');
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'date' => 'required|date|after:today',
                'type' => 'required|string|in:planting,watering,harvesting,fertilizing,pruning,other',
            ], [
                'date.after' => 'Activity date must be in the future (after today).',
                'type.in' => 'Please select a valid activity type.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please correct the errors below.');
            }

            DB::beginTransaction();
            try {
                $activity = $user->activities()->create($request->all());
                
                Log::info('Activity created successfully', [
                    'activity_id' => $activity->id,
                    'user_id' => $user->id,
                    'title' => $activity->title
                ]);

                DB::commit();
                return redirect()->route('activities.index')->with('success', 'Activity created successfully');

            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Failed to create activity', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                    'request_data' => $request->all()
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to create activity. Please try again.');
            }

        } catch (Exception $e) {
            Log::error('Activity store error', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }

    public function edit(Activity $activity)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to edit activities.');
            }

            // Check if user owns this activity
            if ($activity->user_id !== $user->id) {
                Log::warning('User attempted to edit activity they do not own', [
                    'user_id' => $user->id,
                    'activity_id' => $activity->id,
                    'activity_user_id' => $activity->user_id
                ]);
                return redirect()->route('activities.index')->with('error', 'You can only edit your own activities.');
            }

            return view('activities.edit', compact('activity'));

        } catch (Exception $e) {
            Log::error('Activity edit error', [
                'error' => $e->getMessage(),
                'activity_id' => $activity->id ?? null,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('activities.index')
                ->with('error', 'Unable to load activity for editing. Please try again later.');
        }
    }

    public function update(Request $request, Activity $activity)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to update activities.');
            }

            // Check if user owns this activity
            if ($activity->user_id !== $user->id) {
                Log::warning('User attempted to update activity they do not own', [
                    'user_id' => $user->id,
                    'activity_id' => $activity->id,
                    'activity_user_id' => $activity->user_id
                ]);
                return redirect()->route('activities.index')->with('error', 'You can only update your own activities.');
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'date' => 'required|date|after:today',
                'type' => 'required|string|in:planting,watering,harvesting,fertilizing,pruning,other',
            ], [
                'date.after' => 'Activity date must be in the future (after today).',
                'type.in' => 'Please select a valid activity type.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please correct the errors below.');
            }

            DB::beginTransaction();
            try {
                $activity->update($request->all());
                
                Log::info('Activity updated successfully', [
                    'activity_id' => $activity->id,
                    'user_id' => $user->id,
                    'title' => $activity->title
                ]);

                DB::commit();
                return redirect()->route('activities.index')->with('success', 'Activity updated successfully');

            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Failed to update activity', [
                    'error' => $e->getMessage(),
                    'activity_id' => $activity->id,
                    'user_id' => $user->id,
                    'request_data' => $request->all()
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to update activity. Please try again.');
            }

        } catch (Exception $e) {
            Log::error('Activity update error', [
                'error' => $e->getMessage(),
                'activity_id' => $activity->id ?? null,
                'request' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }

    public function destroy(Activity $activity)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to delete activities.');
            }

            // Check if user owns this activity
            if ($activity->user_id !== $user->id) {
                Log::warning('User attempted to delete activity they do not own', [
                    'user_id' => $user->id,
                    'activity_id' => $activity->id,
                    'activity_user_id' => $activity->user_id
                ]);
                return redirect()->route('activities.index')->with('error', 'You can only delete your own activities.');
            }

            DB::beginTransaction();
            try {
                $activityTitle = $activity->title;
                $activity->delete();
                
                Log::info('Activity deleted successfully', [
                    'activity_id' => $activity->id,
                    'user_id' => $user->id,
                    'title' => $activityTitle
                ]);

                DB::commit();
                return redirect()->route('activities.index')->with('success', 'Activity deleted successfully');

            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Failed to delete activity', [
                    'error' => $e->getMessage(),
                    'activity_id' => $activity->id,
                    'user_id' => $user->id
                ]);

                return redirect()->route('activities.index')
                    ->with('error', 'Failed to delete activity. Please try again.');
            }

        } catch (Exception $e) {
            Log::error('Activity destroy error', [
                'error' => $e->getMessage(),
                'activity_id' => $activity->id ?? null,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('activities.index')
                ->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }
}