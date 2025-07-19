<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class ProfileController extends Controller
{
    public function show()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to view your profile.');
            }

            return view('profile', compact('user'));

        } catch (Exception $e) {
            Log::error('Profile show error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Unable to load profile page. Please try again.');
        }
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to update your profile.');
            }

            // Check if this is just a profile picture upload
            $isPictureOnly = $request->hasFile('profile_picture') && 
                           !$request->filled('first_name') && 
                           !$request->filled('last_name') && 
                           !$request->filled('email');

            $rules = [];
            $messages = [];

            if (!$isPictureOnly) {
                $rules = [
                    'first_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                    'last_name'  => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                    'email'      => 'required|email|max:255|unique:users,email,' . $user->id,
                ];
                $messages = [
                    'first_name.required' => 'First name is required.',
                    'first_name.regex' => 'First name can only contain letters and spaces.',
                    'last_name.required' => 'Last name is required.',
                    'last_name.regex' => 'Last name can only contain letters and spaces.',
                    'email.required' => 'Email address is required.',
                    'email.email' => 'Please enter a valid email address.',
                    'email.unique' => 'This email address is already taken.',
                ];
            }

            // Always validate profile picture if present
            $rules['profile_picture'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
            $messages['profile_picture.image'] = 'Profile picture must be an image.';
            $messages['profile_picture.mimes'] = 'Profile picture must be a JPEG, PNG, JPG, or GIF file.';
            $messages['profile_picture.max'] = 'Profile picture must be less than 2MB.';

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput($request->except('profile_picture'))
                    ->with('error', 'Please correct the errors below.');
            }

            // Check if email is already taken by another user
            try {
                $existingUser = \App\Models\User::where('email', $request->email)
                    ->where('id', '!=', $user->id)
                    ->first();
                    
                if ($existingUser) {
                    return redirect()->back()
                        ->withErrors(['email' => 'This email address is already taken.'])
                        ->withInput($request->except('profile_picture'))
                        ->with('error', 'This email address is already taken.');
                }
            } catch (Exception $e) {
                Log::error('Failed to check existing email', [
                    'error' => $e->getMessage(),
                    'email' => $request->email,
                    'user_id' => $user->id
                ]);
            }

            DB::beginTransaction();
            try {
                $oldProfilePicture = $user->profile_picture;
                
                // Only update personal info if not picture-only upload
                if (!$isPictureOnly) {
                    $user->first_name = trim($request->first_name);
                    $user->last_name  = trim($request->last_name);
                    $user->email      = strtolower(trim($request->email));
                }

                // Handle profile picture upload
                if ($request->hasFile('profile_picture')) {
                    try {
                        $file = $request->file('profile_picture');
                        
                        // Validate file
                        if (!$file->isValid()) {
                            throw new Exception('Invalid file upload.');
                        }

                        // Generate unique filename
                        $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                        
                        // Store new file
                        $path = $file->storeAs('profile_pictures', $filename, 'public');
                        
                        if (!$path) {
                            throw new Exception('Failed to store profile picture.');
                        }

                        $user->profile_picture = $path;

                        // Delete old profile picture if exists
                        if ($oldProfilePicture && Storage::disk('public')->exists($oldProfilePicture)) {
                            try {
                                Storage::disk('public')->delete($oldProfilePicture);
                            } catch (Exception $e) {
                                Log::warning('Failed to delete old profile picture', [
                                    'error' => $e->getMessage(),
                                    'old_path' => $oldProfilePicture
                                ]);
                            }
                        }

                    } catch (Exception $e) {
                        Log::error('Failed to handle profile picture upload', [
                            'error' => $e->getMessage(),
                            'user_id' => $user->id,
                            'file_name' => $request->file('profile_picture')->getClientOriginalName()
                        ]);
                        
                        return redirect()->back()
                            ->withInput($request->except('profile_picture'))
                            ->with('error', 'Failed to upload profile picture. Please try again.');
                    }
                }

                $user->save();

                Log::info('Profile updated successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'has_profile_picture' => !empty($user->profile_picture)
                ]);

                DB::commit();

                $successMessage = $isPictureOnly ? 'Profile picture updated successfully!' : 'Profile updated successfully!';
                
                return redirect()->back()
                    ->with('success', $successMessage);

            } catch (Exception $e) {
                DB::rollBack();
                
                Log::error('Failed to update profile', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                    'request_data' => $request->except('profile_picture')
                ]);

                return redirect()->back()
                    ->withInput($request->except('profile_picture'))
                    ->with('error', 'Failed to update profile. Please try again.');
            }

        } catch (Exception $e) {
            Log::error('Profile update error', [
                'error' => $e->getMessage(),
                'request' => $request->except('profile_picture'),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput($request->except('profile_picture'))
                ->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }
} 