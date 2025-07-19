<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use App\Models\Location;
use App\Models\WeatherForecast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        try {
            $stats = $this->getSystemStats();
            
            return view('admin.dashboard', compact('stats'));
        } catch (Exception $e) {
            Log::error('Admin dashboard error', [
                'error' => $e->getMessage(),
                'admin_id' => Auth::id()
            ]);
            
            return redirect()->back()->with('error', 'Unable to load admin dashboard.');
        }
    }

    /**
     * Display all users.
     */
    public function users()
    {
        try {
            $users = User::with(['activities', 'locations'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            
            return view('admin.users.index', compact('users'));
        } catch (Exception $e) {
            Log::error('Admin users list error', [
                'error' => $e->getMessage(),
                'admin_id' => Auth::id()
            ]);
            
            return redirect()->back()->with('error', 'Unable to load users list.');
        }
    }

    /**
     * Show the form for creating a new user.
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function storeUser(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:user,admin',
            ]);

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            Log::info('Admin created new user', [
                'admin_id' => Auth::id(),
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully.');

        } catch (Exception $e) {
            Log::error('Admin create user error', [
                'error' => $e->getMessage(),
                'admin_id' => Auth::id(),
                'request' => $request->all()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to create user. Please try again.');
        }
    }

    /**
     * Show the form for editing a user.
     */
    public function editUser($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('admin.users.edit', compact('user'));
        } catch (Exception $e) {
            Log::error('Admin edit user error', [
                'error' => $e->getMessage(),
                'admin_id' => Auth::id(),
                'user_id' => $id
            ]);
            
            return redirect()->route('admin.users.index')
                ->with('error', 'User not found.');
        }
    }

    /**
     * Update the specified user.
     */
    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'role' => 'required|in:user,admin',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'role' => $request->role,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            Log::info('Admin updated user', [
                'admin_id' => Auth::id(),
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully.');

        } catch (Exception $e) {
            Log::error('Admin update user error', [
                'error' => $e->getMessage(),
                'admin_id' => Auth::id(),
                'user_id' => $id
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to update user. Please try again.');
        }
    }

    /**
     * Delete the specified user.
     */
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent admin from deleting themselves
            if ($user->id === Auth::id()) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'You cannot delete your own account.');
            }

            $user->delete();

            Log::info('Admin deleted user', [
                'admin_id' => Auth::id(),
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully.');

        } catch (Exception $e) {
            Log::error('Admin delete user error', [
                'error' => $e->getMessage(),
                'admin_id' => Auth::id(),
                'user_id' => $id
            ]);
            
            return redirect()->route('admin.users.index')
                ->with('error', 'Unable to delete user. Please try again.');
        }
    }

    /**
     * Display system statistics.
     */
    public function systemStats()
    {
        try {
            $stats = $this->getSystemStats();
            return view('admin.stats', compact('stats'));
        } catch (Exception $e) {
            Log::error('Admin system stats error', [
                'error' => $e->getMessage(),
                'admin_id' => Auth::id()
            ]);
            
            return redirect()->back()->with('error', 'Unable to load system statistics.');
        }
    }

    /**
     * Get system statistics.
     */
    private function getSystemStats()
    {
        return [
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_regular_users' => User::where('role', 'user')->count(),
            'total_activities' => Activity::count(),
            'total_locations' => Location::count(),
            'total_weather_records' => WeatherForecast::count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_user_activities' => User::with(['activities' => function($query) {
                $query->latest()->take(3);
            }])->has('activities')->latest()->take(5)->get(),
            'users_by_role' => [
                'admin' => User::where('role', 'admin')->count(),
                'user' => User::where('role', 'user')->count(),
            ],
            'activities_by_type' => Activity::selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->get()
                ->pluck('count', 'type')
                ->toArray(),
        ];
    }
} 