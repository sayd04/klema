<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        try {
            // Check if user is already authenticated
            if (Auth::check()) {
                return redirect()->route('dashboard');
            }

            return view('auth.register');

        } catch (Exception $e) {
            Log::error('Registration form error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('auth.register')->with('error', 'Unable to load registration page. Please try again.');
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'last_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ], [
                'first_name.required' => 'First name is required.',
                'first_name.regex' => 'First name can only contain letters and spaces.',
                'last_name.required' => 'Last name is required.',
                'last_name.regex' => 'Last name can only contain letters and spaces.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email address is already registered.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
                'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput($request->except('password', 'password_confirmation'))
                    ->with('error', 'Please correct the errors below.');
            }

            // Check if user already exists
            try {
                $existingUser = User::where('email', $request->email)->first();
                if ($existingUser) {
                    return redirect()->back()
                        ->withErrors(['email' => 'This email address is already registered.'])
                        ->withInput($request->except('password', 'password_confirmation'))
                        ->with('error', 'This email address is already registered.');
                }
            } catch (Exception $e) {
                Log::error('Failed to check existing user', [
                    'error' => $e->getMessage(),
                    'email' => $request->email
                ]);
            }

            DB::beginTransaction();
            try {
                $user = User::create([
                    'first_name' => trim($request->first_name),
                    'last_name' => trim($request->last_name),
                    'email' => strtolower(trim($request->email)),
                    'password' => Hash::make($request->password),
                ]);

                // Log successful registration
                Log::info('User registered successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip()
                ]);

                // Auto-login the user
                Auth::login($user);

                DB::commit();

                return redirect()->route('dashboard')
                    ->with('success', 'Welcome to Farm Operation Management, ' . $user->first_name . '! Your account has been created successfully.');

            } catch (Exception $e) {
                DB::rollBack();
                
                Log::error('Failed to create user', [
                    'error' => $e->getMessage(),
                    'request_data' => $request->except('password', 'password_confirmation'),
                    'ip' => $request->ip()
                ]);

                return redirect()->back()
                    ->withInput($request->except('password', 'password_confirmation'))
                    ->with('error', 'Failed to create account. Please try again.');
            }

        } catch (Exception $e) {
            Log::error('Registration error', [
                'error' => $e->getMessage(),
                'request' => $request->except('password', 'password_confirmation'),
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'An unexpected error occurred during registration. Please try again later.');
        }
    }
}