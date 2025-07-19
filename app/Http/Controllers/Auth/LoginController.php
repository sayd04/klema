<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Exception;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        try {
            // Check if user is already authenticated
            if (Auth::check()) {
                return redirect()->route('dashboard');
            }

            return view('auth.login');

        } catch (Exception $e) {
            Log::error('Login form error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('auth.login')->with('error', 'Unable to load login page. Please try again.');
        }
    }

    public function login(Request $request)
    {
        try {
            // Rate limiting
            $key = 'login_' . $request->ip();
            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                Log::warning('Login rate limit exceeded', [
                    'ip' => $request->ip(),
                    'seconds_remaining' => $seconds
                ]);
                
                throw ValidationException::withMessages([
                    'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
                ]);
            }

            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8',
            ], [
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 8 characters.',
            ]);

            if ($validator->fails()) {
                RateLimiter::hit($key);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput($request->only('email'))
                    ->with('error', 'Please correct the errors below.');
            }

            $credentials = $request->only('email', 'password');
            $remember = $request->boolean('remember');

            try {
                if (Auth::attempt($credentials, $remember)) {
                    $user = Auth::user();
                    
                    // Clear rate limiter on successful login
                    RateLimiter::clear($key);
                    
                    $request->session()->regenerate();
                    
                    Log::info('User logged in successfully', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'ip' => $request->ip()
                    ]);

                    return redirect()->intended(route('dashboard'))
                        ->with('success', 'Welcome back, ' . $user->first_name . '!');

                } else {
                    RateLimiter::hit($key);
                    
                    Log::warning('Failed login attempt', [
                        'email' => $request->email,
                        'ip' => $request->ip()
                    ]);

                    return redirect()->back()
                        ->withErrors([
                            'email' => 'The provided credentials do not match our records.',
                        ])
                        ->withInput($request->only('email'))
                        ->with('error', 'Invalid credentials. Please try again.');
                }

            } catch (Exception $e) {
                RateLimiter::hit($key);
                
                Log::error('Login authentication error', [
                    'error' => $e->getMessage(),
                    'email' => $request->email,
                    'ip' => $request->ip()
                ]);

                return redirect()->back()
                    ->withErrors([
                        'email' => 'Authentication service temporarily unavailable.',
                    ])
                    ->withInput($request->only('email'))
                    ->with('error', 'Unable to authenticate. Please try again later.');
            }

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->only('email'))
                ->with('error', 'Please correct the errors below.');

        } catch (Exception $e) {
            Log::error('Login error', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user) {
                Log::info('User logged out', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip()
                ]);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('welcome')
                ->with('success', 'You have been successfully logged out.');

        } catch (Exception $e) {
            Log::error('Logout error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            // Force logout even if there's an error
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('welcome')
                ->with('error', 'Logged out with some issues. Please login again if needed.');
        }
    }
}