@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="glass-card rounded-2xl overflow-hidden mb-8">
            <div class="gradient-bg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Profile Settings</h1>
                        <p class="text-green-100 mt-1">Manage your personal information and preferences</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-circle text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="glass-card rounded-2xl p-4 mb-6 bg-green-50 border border-green-200">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="glass-card rounded-2xl p-4 mb-6 bg-red-50 border border-red-200">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                <span class="text-red-800 font-medium">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Picture -->
            <div class="lg:col-span-1">
                <div class="glass-card rounded-2xl p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Profile Picture</h2>
                    <div class="text-center">
                        <div class="relative inline-block mb-6">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" 
                                     alt="Profile Picture" 
                                     class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                            @else
                                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center border-4 border-white shadow-lg">
                                    <span class="text-white font-bold text-3xl">{{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="absolute -bottom-2 -right-2 bg-green-500 rounded-full p-2 shadow-lg hover:bg-green-600 transition-colors cursor-pointer">
                                <i class="fas fa-camera text-white text-sm"></i>
                            </div>
                        </div>
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="pictureForm">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <label for="profile_picture" class="cursor-pointer">
                                <div class="inline-flex items-center px-4 py-2 border border-green-200 text-sm font-medium rounded-xl text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                                    <i class="fas fa-upload mr-2"></i>
                                    Change Photo
                                </div>
                                <input type="file" name="profile_picture" id="profile_picture" class="hidden" accept="image/*" onchange="this.form.submit()">
                            </label>
                        </form>
                        @error('profile_picture')
                        <div class="mt-2 text-red-600 text-sm">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </div>
                        @enderror
                        <p class="text-xs text-gray-500 mt-2">Supported formats: JPG, PNG, GIF (max 2MB)</p>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="lg:col-span-2">
                <div class="glass-card rounded-2xl p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-user-edit text-green-600 mr-3"></i>
                        Personal Information
                    </h2>
                    
                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-user text-green-500 mr-2"></i>
                                    First Name
                                </label>
                                <input 
                                    type="text" 
                                    name="first_name" 
                                    value="{{ old('first_name', Auth::user()->first_name) }}" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70" 
                                    required
                                >
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-user text-green-500 mr-2"></i>
                                    Last Name
                                </label>
                                <input 
                                    type="text" 
                                    name="last_name" 
                                    value="{{ old('last_name', Auth::user()->last_name) }}" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70" 
                                    required
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-envelope text-green-500 mr-2"></i>
                                Email Address
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ old('email', Auth::user()->email) }}" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70" 
                                required
                            >
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <div class="text-sm text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Your information is secure and private
                            </div>
                            <button 
                                type="submit" 
                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                            >
                                <i class="fas fa-save mr-2"></i>
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="glass-card rounded-2xl p-6 mt-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-info-circle text-green-600 mr-3"></i>
                Account Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="glass-card rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-gray-800">Member Since</h3>
                            <p class="text-sm text-gray-600">{{ Auth::user()->created_at->format('F j, Y') }}</p>
                        </div>
                        <div class="text-green-500">
                            <i class="fas fa-calendar-alt text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="glass-card rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-gray-800">Last Updated</h3>
                            <p class="text-sm text-gray-600">{{ Auth::user()->updated_at->format('F j, Y') }}</p>
                        </div>
                        <div class="text-green-500">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview image before upload
    document.getElementById('profile_picture').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Show loading state
            const uploadButton = document.querySelector('label[for="profile_picture"] div');
            const originalText = uploadButton.innerHTML;
            uploadButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...';
            uploadButton.classList.add('opacity-50');
            
            const reader = new FileReader();
            reader.onload = function(e) {
                // Update the preview image
                const preview = document.querySelector('.relative.inline-block img');
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    const initialsDiv = document.querySelector('.relative.inline-block div');
                    if (initialsDiv) {
                        initialsDiv.innerHTML = `<img src="${e.target.result}" alt="Profile" class="w-full h-full rounded-full object-cover">`;
                    }
                }
            };
            reader.readAsDataURL(file);
            
            // Submit the form
            setTimeout(() => {
                document.getElementById('pictureForm').submit();
            }, 100);
        }
    });

    // Form validation for personal info form
    document.querySelector('form[action="{{ route("profile.update") }}"]:not(#pictureForm)').addEventListener('submit', function(e) {
        const firstName = document.querySelector('input[name="first_name"]').value.trim();
        const lastName = document.querySelector('input[name="last_name"]').value.trim();
        const email = document.querySelector('input[name="email"]').value.trim();

        if (!firstName || !lastName || !email) {
            e.preventDefault();
            showToast('Please fill in all required fields', 'error');
        }
    });
</script>
@endpush
@endsection