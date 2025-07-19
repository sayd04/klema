@extends('layouts.app')

@section('title', 'Admin Dashboard - Farm Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-slate-50 to-zinc-50 dark:from-gray-900 dark:via-slate-900 dark:to-zinc-900">
    <div class="w-full">
        <!-- Admin Welcome Header -->
        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-3xl p-8 mb-8 border border-white/20 shadow-2xl mx-6 backdrop-blur-xl">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between px-8">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-black text-white mb-2">
                        Admin Dashboard 
                        <span class="text-4xl animate-float inline-block">👨‍💼</span>
                    </h1>
                    <p class="text-xl text-purple-100">System overview and user management for {{ now()->format('l, F j') }}</p>
                </div>
                <div class="mt-6 lg:mt-0">
                    <div class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-xl rounded-2xl border border-white/30 shadow-lg">
                        <i class="fas fa-shield-alt text-white mr-3 text-lg"></i>
                        <span class="font-bold text-lg text-white">Admin Access</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Statistics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mx-6">
            <!-- Total Users -->
            <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-3xl p-6 border border-white/20 shadow-xl hover:scale-105 transition-all duration-500 backdrop-blur-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-100">Total Users</p>
                        <p class="text-4xl font-black text-white mt-2">{{ $stats['total_users'] }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Admins -->
            <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-3xl p-6 border border-white/20 shadow-xl hover:scale-105 transition-all duration-500 backdrop-blur-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-100">Total Admins</p>
                        <p class="text-4xl font-black text-white mt-2">{{ $stats['total_admins'] }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-shield text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Activities -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl p-6 border border-white/20 shadow-xl hover:scale-105 transition-all duration-500 backdrop-blur-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-100">Total Activities</p>
                        <p class="text-4xl font-black text-white mt-2">{{ $stats['total_activities'] }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-tasks text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Locations -->
            <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-3xl p-6 border border-white/20 shadow-xl hover:scale-105 transition-all duration-500 backdrop-blur-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-100">Total Locations</p>
                        <p class="text-4xl font-black text-white mt-2">{{ $stats['total_locations'] }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8 px-8">
            <!-- Recent Users -->
            <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-3xl overflow-hidden border border-white/20 shadow-2xl flex flex-col aspect-square min-h-[20rem] max-w-xs w-full mx-auto hover:shadow-2xl hover:drop-shadow-lg transition-all duration-300 backdrop-blur-xl">
                <div class="p-6 text-white relative overflow-hidden w-full">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-cyan-500/20"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl font-black flex items-center">
                                <i class="fas fa-users mr-3 text-2xl"></i>
                                Recent Users
                            </h3>
                            <a href="{{ route('admin.users.index') }}" class="text-blue-100 hover:text-white text-lg font-bold flex items-center group">
                                View all
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="p-6 w-full flex-1 flex flex-col justify-center">
                    @if($stats['recent_users']->count() > 0)
                    <div class="space-y-3">
                        @foreach($stats['recent_users'] as $user)
                        <div class="flex items-center p-3 bg-white/10 backdrop-blur-xl rounded-2xl hover:bg-white/20 transition-all duration-300 border border-white/20 group hover:drop-shadow-md">
                            <div class="flex-shrink-0 w-10 h-10 rounded-2xl flex items-center justify-center mr-3 shadow-lg
                                {{ $user->role === 'admin' ? 'bg-gradient-to-br from-purple-400 to-pink-500' : 'bg-gradient-to-br from-blue-400 to-cyan-500' }}">
                                <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : 'user' }} text-white text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-white text-sm truncate">{{ $user->full_name }}</h4>
                                <div class="flex items-center justify-between mt-1">
                                    <p class="text-blue-100 truncate text-xs">
                                        {{ $user->email }}
                                    </p>
                                    <span class="text-xs text-blue-200 ml-2 whitespace-nowrap font-medium">
                                        {{ $user->role }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <p class="text-blue-100 text-base mb-3">No users found</p>
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-white/20 text-white rounded-2xl hover:bg-white/30 transition-all duration-300 shadow-lg font-bold text-base">
                            <i class="fas fa-plus mr-2"></i>
                            Add First User
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent User Activities -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl overflow-hidden border border-white/20 shadow-2xl flex flex-col aspect-square min-h-[20rem] max-w-xs w-full mx-auto hover:shadow-2xl hover:drop-shadow-lg transition-all duration-300 backdrop-blur-xl">
                <div class="p-6 text-white relative overflow-hidden w-full">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-500/20 to-emerald-500/20"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl font-black flex items-center">
                                <i class="fas fa-history mr-3 text-2xl"></i>
                                Recent User Activities
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="p-6 w-full flex-1 flex flex-col justify-center">
                    @if($stats['recent_user_activities']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['recent_user_activities'] as $user)
                        <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-3 border border-white/20">
                            <div class="flex items-center mb-2">
                                <div class="flex-shrink-0 w-8 h-8 rounded-xl flex items-center justify-center mr-3 shadow-lg
                                    {{ $user->role === 'admin' ? 'bg-gradient-to-br from-purple-400 to-pink-500' : 'bg-gradient-to-br from-blue-400 to-cyan-500' }}">
                                    <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : 'user' }} text-white text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-white text-sm truncate">{{ $user->full_name }}</h4>
                                    <p class="text-green-100 text-xs">{{ $user->email }}</p>
                                </div>
                            </div>
                            @if($user->activities->count() > 0)
                            <div class="space-y-2">
                                @foreach($user->activities as $activity)
                                <div class="flex items-center p-2 bg-white/5 rounded-lg">
                                    <div class="flex-shrink-0 w-6 h-6 rounded-lg flex items-center justify-center mr-2 shadow-sm
                                        {{ $activity->type == 'planting' ? 'bg-gradient-to-br from-green-400 to-emerald-500' : 
                                           ($activity->type == 'watering' ? 'bg-gradient-to-br from-blue-400 to-cyan-500' : 
                                           ($activity->type == 'harvesting' ? 'bg-gradient-to-br from-yellow-400 to-orange-500' : 
                                           ($activity->type == 'fertilizing' ? 'bg-gradient-to-br from-purple-400 to-pink-500' : 
                                           'bg-gradient-to-br from-gray-400 to-gray-500'))) }}">
                                        <i class="fas fa-{{ $activity->type == 'planting' ? 'seedling' : 
                                              ($activity->type == 'watering' ? 'tint' : 
                                              ($activity->type == 'harvesting' ? 'cut' : 
                                              ($activity->type == 'fertilizing' ? 'leaf' : 'tasks'))) }} text-white text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white text-xs font-medium truncate">{{ $activity->title }}</p>
                                        <p class="text-green-200 text-xs">{{ \Carbon\Carbon::parse($activity->date)->format('M d') }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-green-200 text-xs italic">No recent activities</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <i class="fas fa-tasks text-white text-xl"></i>
                        </div>
                        <p class="text-green-100 text-base mb-3">No user activities found</p>
                        <p class="text-xs text-green-200">Users will appear here when they create activities</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Admin Quick Actions -->
        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-3xl p-8 border border-white/20 shadow-2xl mx-6 backdrop-blur-xl">
            <div>
                <h3 class="text-2xl font-black text-white mb-8 flex items-center">
                    <i class="fas fa-cogs text-yellow-400 mr-4 text-2xl"></i>
                    Admin Actions
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <a href="{{ route('admin.users.index') }}" class="group flex flex-col items-center justify-center p-6 bg-white/10 rounded-2xl hover:bg-white/20 transition-all duration-300 border border-white/20 hover:scale-105 shadow-lg">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                        <span class="text-lg font-bold text-white text-center">Manage Users</span>
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="group flex flex-col items-center justify-center p-6 bg-white/10 rounded-2xl hover:bg-white/20 transition-all duration-300 border border-white/20 hover:scale-105 shadow-lg">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-user-plus text-white text-2xl"></i>
                        </div>
                        <span class="text-lg font-bold text-white text-center">Add User</span>
                    </a>
                    <a href="{{ route('admin.stats') }}" class="group flex flex-col items-center justify-center p-6 bg-white/10 rounded-2xl hover:bg-white/20 transition-all duration-300 border border-white/20 hover:scale-105 shadow-lg">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-chart-bar text-white text-2xl"></i>
                        </div>
                        <span class="text-lg font-bold text-white text-center">System Stats</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="group flex flex-col items-center justify-center p-6 bg-white/10 rounded-2xl hover:bg-white/20 transition-all duration-300 border border-white/20 hover:scale-105 shadow-lg">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-home text-white text-2xl"></i>
                        </div>
                        <span class="text-lg font-bold text-white text-center">User Dashboard</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 