@extends('layouts.app')

@section('title', 'System Statistics - Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-slate-50 to-zinc-50 dark:from-gray-900 dark:via-slate-900 dark:to-zinc-900">
    <div class="w-full">
        <!-- Header -->
        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-3xl p-8 mb-8 border border-white/20 shadow-2xl mx-6 backdrop-blur-xl">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between px-8">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-black text-white mb-2">
                        System Statistics 
                        <span class="text-4xl animate-float inline-block">📊</span>
                    </h1>
                    <p class="text-xl text-purple-100">Comprehensive system overview and analytics</p>
                </div>
                <div class="mt-6 lg:mt-0">
                    <div class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-xl rounded-2xl border border-white/30 shadow-lg">
                        <i class="fas fa-chart-line text-white mr-3 text-lg"></i>
                        <span class="font-bold text-lg text-white">Real-time Data</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mx-6">
            <!-- Total Users -->
            <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-3xl p-6 border border-white/20 shadow-xl hover:scale-105 transition-all duration-500 backdrop-blur-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-100">Total Users</p>
                        <p class="text-4xl font-black text-white mt-2">{{ $stats['total_users'] }}</p>
                        <p class="text-xs text-blue-200 mt-1">Registered accounts</p>
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
                        <p class="text-xs text-purple-200 mt-1">Administrative users</p>
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
                        <p class="text-xs text-green-200 mt-1">Farm activities recorded</p>
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
                        <p class="text-xs text-orange-200 mt-1">Farm locations</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Statistics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8 px-8">
            <!-- User Distribution -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-users text-blue-600 mr-3"></i>
                    User Distribution
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-2xl">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-user text-white text-lg"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Regular Users</p>
                                <p class="text-sm text-gray-600">Standard farm management users</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-blue-600">{{ $stats['users_by_role']['user'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500">{{ $stats['total_users'] > 0 ? round(($stats['users_by_role']['user'] ?? 0) / $stats['total_users'] * 100, 1) : 0 }}%</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-purple-50 rounded-2xl">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-user-shield text-white text-lg"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Administrators</p>
                                <p class="text-sm text-gray-600">System administrators</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-purple-600">{{ $stats['users_by_role']['admin'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500">{{ $stats['total_users'] > 0 ? round(($stats['users_by_role']['admin'] ?? 0) / $stats['total_users'] * 100, 1) : 0 }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Types -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-tasks text-green-600 mr-3"></i>
                    Activity Types
                </h3>
                <div class="space-y-4">
                    @if(isset($stats['activities_by_type']) && count($stats['activities_by_type']) > 0)
                        @foreach($stats['activities_by_type'] as $type => $count)
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-2xl">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-{{ $type == 'planting' ? 'seedling' : 
                                          ($type == 'watering' ? 'tint' : 
                                          ($type == 'harvesting' ? 'cut' : 
                                          ($type == 'fertilizing' ? 'leaf' : 'tasks'))) }} text-white text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ ucfirst($type) }}</p>
                                    <p class="text-sm text-gray-600">{{ $type }} activities</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-green-600">{{ $count }}</p>
                                <p class="text-sm text-gray-500">{{ $stats['total_activities'] > 0 ? round($count / $stats['total_activities'] * 100, 1) : 0 }}%</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chart-bar text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500">No activity data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Additional Statistics -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-8 mx-6 mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-chart-line text-purple-600 mr-3"></i>
                Additional Statistics
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-cloud-sun text-white text-xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Weather Records</h4>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_weather_records'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Weather data entries</p>
                </div>
                <div class="text-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Active Users</h4>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['total_regular_users'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Regular farm users</p>
                </div>
                <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl">
                    <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-database text-white text-xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">System Health</h4>
                    <p class="text-3xl font-bold text-purple-600">100%</p>
                    <p class="text-sm text-gray-500 mt-1">System operational</p>
                </div>
            </div>
        </div>

        <!-- Back to Dashboard -->
        <div class="text-center mb-8">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-semibold rounded-2xl hover:from-purple-600 hover:to-indigo-700 transition-all duration-300 shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Admin Dashboard
            </a>
        </div>
    </div>
</div>
@endsection 