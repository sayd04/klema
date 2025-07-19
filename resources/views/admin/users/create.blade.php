@extends('layouts.app')

@section('title', 'Create User - Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-slate-50 to-zinc-50 dark:from-gray-900 dark:via-slate-900 dark:to-zinc-900">
    <div class="w-full">
        <!-- Header -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl p-8 mb-8 border border-white/20 shadow-2xl mx-6 backdrop-blur-xl">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between px-8">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-black text-white mb-2">
                        Create New User 
                        <span class="text-4xl animate-float inline-block">👤</span>
                    </h1>
                    <p class="text-xl text-green-100">Add a new user to the system</p>
                </div>
                <div class="mt-6 lg:mt-0">
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-6 py-3 bg-white/20 text-white rounded-2xl hover:bg-white/30 transition-all duration-300 shadow-lg font-bold text-lg">
                        <i class="fas fa-arrow-left mr-3"></i>
                        Back to Users
                    </a>
                </div>
            </div>
        </div>

        <!-- Create User Form -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl mx-6 mb-8 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   value="{{ old('first_name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-300"
                                   placeholder="Enter first name"
                                   required>
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   value="{{ old('last_name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-300"
                                   placeholder="Enter last name"
                                   required>
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-300"
                               placeholder="Enter email address"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select id="role" 
                                name="role" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-300"
                                required>
                            <option value="">Select role</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-300"
                                   placeholder="Enter password"
                                   required>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-300"
                                   placeholder="Confirm password"
                                   required>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-4 pt-6">
                        <a href="{{ route('admin.users.index') }}" 
                           class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300 font-semibold">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-2xl hover:from-green-600 hover:to-emerald-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-user-plus mr-2"></i>
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Role Information -->
        <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-3xl p-8 border border-white/20 shadow-2xl mx-6 backdrop-blur-xl">
            <h3 class="text-2xl font-black text-white mb-4 flex items-center">
                <i class="fas fa-info-circle mr-3 text-2xl"></i>
                Role Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-white">
                <div class="bg-white/10 rounded-2xl p-6 backdrop-blur-xl">
                    <h4 class="font-bold text-lg mb-2 flex items-center">
                        <i class="fas fa-user mr-2"></i>
                        User Role
                    </h4>
                    <p class="text-blue-100">Regular users can access farm management features, track activities, view weather, and generate reports.</p>
                </div>
                <div class="bg-white/10 rounded-2xl p-6 backdrop-blur-xl">
                    <h4 class="font-bold text-lg mb-2 flex items-center">
                        <i class="fas fa-user-shield mr-2"></i>
                        Admin Role
                    </h4>
                    <p class="text-blue-100">Administrators have full system access including user management, system statistics, and administrative functions.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 