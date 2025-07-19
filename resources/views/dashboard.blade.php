@extends('layouts.app')

@section('title', 'Dashboard - Farm Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-slate-50 to-zinc-50 dark:from-gray-900 dark:via-slate-900 dark:to-zinc-900">
    <div class="w-full">
        <!-- Modern Welcome Header -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl p-8 mb-8 border border-white/20 shadow-2xl mx-6 backdrop-blur-xl" data-dashboard="header">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between px-8">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-black text-white mb-2">
                        Welcome back, {{ auth()->user()->first_name }}! 
                        <span class="text-4xl animate-float inline-block">🌱</span>
                    </h1>
                    <p class="text-xl text-green-100" data-clock="date">Here's your farm overview for {{ now()->format('l, F j') }}</p>
                </div>
                <div class="mt-6 lg:mt-0">
                    <div class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-xl rounded-2xl border border-white/30 shadow-lg">
                        <i class="fas fa-clock text-white mr-3 text-lg"></i>
                        <span class="font-bold text-lg text-white" data-clock="time">{{ now()->format('g:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Weather Advisory -->
        @if($currentWeather)
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl overflow-hidden mb-8 border border-white/20 shadow-2xl mx-6 backdrop-blur-xl">
            <div class="p-8 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-green-500/20 to-emerald-500/20"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-black flex items-center">
                                <i class="fas fa-cloud-sun mr-3 text-2xl"></i>
                                Weather Advisory
                            </h3>
                            <p class="text-green-100 mt-2 text-lg">Current conditions for your farm</p>
                        </div>
                        <div class="text-6xl font-black">{{ round($currentWeather['main']['temp']) }}°C</div>
                    </div>
                </div>
            </div>
            <div class="p-8">
                <div class="flex flex-col lg:flex-row items-center justify-between">
                    <div class="flex items-center space-x-6 mb-6 lg:mb-0">
                        @if(isset($currentWeather['weather'][0]['icon']))
                            <img src="http://openweathermap.org/img/wn/{{ $currentWeather['weather'][0]['icon'] }}@2x.png" alt="Weather" class="w-20 h-20">
                        @endif
                        <div>
                            <div class="text-2xl font-bold capitalize text-white">{{ $currentWeather['weather'][0]['description'] ?? 'Unknown' }}</div>
                            <div class="text-lg text-green-100">Humidity: {{ $currentWeather['main']['humidity'] }}%</div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 rounded-2xl p-6">
                        <div class="flex items-center text-green-600 dark:text-green-400 mb-3">
                            <i class="fas fa-lightbulb mr-3 text-xl"></i>
                            <span class="font-bold text-lg">Farming Tip</span>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 text-lg">
                            @if(str_contains(strtolower($currentWeather['weather'][0]['description'] ?? ''), 'rain'))
                                It might rain today. Consider indoor tasks or avoid watering.
                            @elseif(str_contains(strtolower($currentWeather['weather'][0]['description'] ?? ''), 'sun'))
                                Perfect weather for outdoor activities and crop maintenance.
                            @else
                                Check the detailed weather forecast for specific farming recommendations.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Modern Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mx-6">
            <!-- Total Activities -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl p-6 border border-white/20 shadow-xl hover:scale-105 transition-all duration-500 backdrop-blur-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-100">Total Activities</p>
                        <p class="text-4xl font-black text-white mt-2">{{ $activities->count() }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-tasks text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- This Week -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl p-6 border border-white/20 shadow-xl hover:scale-105 transition-all duration-500 backdrop-blur-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-100">This Week</p>
                        <p class="text-4xl font-black text-white mt-2">{{ $activities->where('date', '>=', now()->startOfWeek())->count() }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-week text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Weather Summary -->
            @if($weatherSummary)
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl p-6 border border-white/20 shadow-xl hover:scale-105 transition-all duration-500 backdrop-blur-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-100">Avg Temperature</p>
                        <p class="text-4xl font-black text-white mt-2">{{ $weatherSummary['avg_temperature'] }}°C</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-thermometer-half text-white text-2xl"></i>
                    </div>
                </div>
            </div>
            @endif

            <!-- Rainy Days -->
            @if($weatherSummary)
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl p-6 border border-white/20 shadow-xl hover:scale-105 transition-all duration-500 backdrop-blur-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-100">Rainy Days</p>
                        <p class="text-4xl font-black text-white mt-2">{{ $weatherSummary['rainy_days'] }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-cloud-rain text-white text-2xl"></i>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Weather Summary Section -->
        @if($weatherSummary)
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl overflow-hidden border border-white/20 shadow-2xl mb-8 mx-6 backdrop-blur-xl">
            <div class="p-8 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-green-500/20 to-emerald-500/20"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-black flex items-center">
                        <i class="fas fa-chart-line mr-3 text-2xl"></i>
                        30-Day Weather Summary
                    </h3>
                    <p class="text-green-100 mt-2">Based on collected weather data</p>
                </div>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-black text-white">{{ $weatherSummary['avg_temperature'] }}°C</div>
                        <div class="text-sm font-semibold text-green-100 mt-1">Avg Temperature</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-black text-white">{{ $weatherSummary['avg_humidity'] }}%</div>
                        <div class="text-sm font-semibold text-green-100 mt-1">Avg Humidity</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-black text-white">{{ $weatherSummary['rainy_days'] }} days</div>
                        <div class="text-sm font-semibold text-green-100 mt-1">Rainy Days</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-black text-white">{{ $weatherSummary['sunny_days'] }} days</div>
                        <div class="text-sm font-semibold text-green-100 mt-1">Sunny Days</div>
                    </div>
                </div>
                <div class="mt-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl border border-green-200 dark:border-green-700">
                    <div class="flex items-center text-green-700 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span class="font-bold text-base">Weather Pattern</span>
                    </div>
                    <p class="text-gray-800">
                        <span class="font-semibold">Most common weather:</span> <strong>{{ $weatherSummary['most_common_weather'] }}</strong> | 
                        <span class="font-semibold">Temperature range:</span> <strong>{{ $weatherSummary['min_temperature'] }}°C - {{ $weatherSummary['max_temperature'] }}°C</strong>
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Enhanced Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8 px-8">
            <!-- Modern Recent Activities -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl overflow-hidden border border-white/20 shadow-2xl flex flex-col aspect-square min-h-[20rem] max-w-xs w-full mx-auto hover:shadow-2xl hover:drop-shadow-lg transition-all duration-300 backdrop-blur-xl">
                <div class="p-6 text-white relative overflow-hidden w-full">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-500/20 to-emerald-500/20"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl font-black flex items-center">
                                <i class="fas fa-history mr-3 text-2xl"></i>
                                Recent Activities
                            </h3>
                            <a href="{{ route('activities.index') }}" class="text-green-100 hover:text-white text-lg font-bold flex items-center group">
                                View all
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="p-6 w-full flex-1 flex flex-col justify-center">
                    @if($activities->count() > 0)
                    <div class="space-y-3">
                        @foreach($activities->take(5) as $activity)
                        <div class="flex items-center p-3 bg-white/10 backdrop-blur-xl rounded-2xl hover:bg-white/20 transition-all duration-300 border border-white/20 group hover:drop-shadow-md">
                            <div class="flex-shrink-0 w-10 h-10 rounded-2xl flex items-center justify-center mr-3 shadow-lg
                                {{ $activity->type == 'planting' ? 'bg-gradient-to-br from-green-400 to-emerald-500' : 
                                   ($activity->type == 'watering' ? 'bg-gradient-to-br from-blue-400 to-cyan-500' : 
                                   ($activity->type == 'harvesting' ? 'bg-gradient-to-br from-yellow-400 to-orange-500' : 
                                   ($activity->type == 'fertilizing' ? 'bg-gradient-to-br from-purple-400 to-pink-500' : 
                                   'bg-gradient-to-br from-gray-400 to-gray-500'))) }}">
                                <i class="fas fa-{{ $activity->type == 'planting' ? 'seedling' : 
                                      ($activity->type == 'watering' ? 'tint' : 
                                      ($activity->type == 'harvesting' ? 'cut' : 
                                      ($activity->type == 'fertilizing' ? 'leaf' : 'tasks'))) }} text-white text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-white text-sm truncate">{{ $activity->title }}</h4>
                                <div class="flex items-center justify-between mt-1">
                                    <p class="text-green-100 truncate text-xs">
                                        {{ Str::limit($activity->description, 24) }}
                                    </p>
                                    <span class="text-xs text-green-200 ml-2 whitespace-nowrap font-medium">
                                        {{ \Carbon\Carbon::parse($activity->date)->format('M d') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <i class="fas fa-seedling text-white text-xl"></i>
                        </div>
                        <p class="text-green-100 text-base mb-3">No recent activities found</p>
                        <a href="{{ route('activities.create') }}" class="inline-flex items-center px-4 py-2 bg-white/20 text-white rounded-2xl hover:bg-white/30 transition-all duration-300 shadow-lg font-bold text-base">
                            <i class="fas fa-plus mr-2"></i>
                            Add First Activity
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Weather Summary Card -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl overflow-hidden border border-white/20 shadow-2xl flex flex-col aspect-square min-h-[20rem] max-w-xs w-full mx-auto hover:shadow-2xl hover:drop-shadow-lg transition-all duration-300 backdrop-blur-xl">
                <div class="p-6 text-white relative overflow-hidden w-full">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-500/20 to-emerald-500/20"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl font-black flex items-center">
                                <i class="fas fa-cloud-sun mr-3 text-2xl"></i>
                                Weather Summary
                            </h3>
                            <a href="{{ route('weather') }}" class="text-green-100 hover:text-white text-lg font-bold flex items-center group">
                                View details
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                        @if($defaultLocation)
                        <p class="text-green-100 mt-2">{{ $defaultLocation->name }}</p>
                        @endif
                    </div>
                </div>
                <div class="p-6 w-full flex-1 flex flex-col justify-center">
                    @if($currentWeather)
                    <div class="text-center">
                        <div class="text-4xl font-black text-white mb-2">{{ round($currentWeather['main']['temp']) }}°C</div>
                        <div class="text-lg text-green-100 mb-4">{{ $currentWeather['weather'][0]['description'] ?? 'Unknown' }}</div>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-green-200">Humidity</div>
                                <div class="text-white font-bold">{{ $currentWeather['main']['humidity'] }}%</div>
                            </div>
                            <div>
                                <div class="text-green-200">Wind</div>
                                <div class="text-white font-bold">{{ round($currentWeather['wind']['speed'] * 3.6) }} km/h</div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <i class="fas fa-cloud-sun text-white text-xl"></i>
                        </div>
                        <p class="text-green-100 text-base mb-2">Weather data loading...</p>
                        <p class="text-xs text-green-200">Please add a location in the Weather section</p>
                        <a href="{{ route('weather') }}" class="inline-flex items-center px-4 py-2 bg-white/20 text-white rounded-2xl hover:bg-white/30 transition-all duration-300 shadow-lg font-bold text-base mt-2">
                            <i class="fas fa-plus mr-2"></i>
                            Add Location
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modern Quick Actions -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl p-8 border border-white/20 shadow-2xl mx-6 backdrop-blur-xl">
            <div>
                <h3 class="text-2xl font-black text-white mb-8 flex items-center">
                    <i class="fas fa-bolt text-yellow-400 mr-4 text-2xl"></i>
                    Quick Actions
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <a href="{{ route('activities.create') }}" class="group flex flex-col items-center justify-center p-6 bg-white/10 rounded-2xl hover:bg-white/20 transition-all duration-300 border border-white/20 hover:scale-105 shadow-lg">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-plus text-white text-2xl"></i>
                        </div>
                        <span class="text-lg font-bold text-white text-center">Add Activity</span>
                    </a>
                    <a href="{{ route('weather') }}" class="group flex flex-col items-center justify-center p-6 bg-white/10 rounded-2xl hover:bg-white/20 transition-all duration-300 border border-white/20 hover:scale-105 shadow-lg">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-cloud-sun text-white text-2xl"></i>
                        </div>
                        <span class="text-lg font-bold text-white text-center">Weather</span>
                    </a>
                    <a href="{{ route('reports') }}" class="group flex flex-col items-center justify-center p-6 bg-white/10 rounded-2xl hover:bg-white/20 transition-all duration-300 border border-white/20 hover:scale-105 shadow-lg">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-chart-bar text-white text-2xl"></i>
                        </div>
                        <span class="text-lg font-bold text-white text-center">Reports</span>
                    </a>
                    <a href="{{ route('profile') }}" class="group flex flex-col items-center justify-center p-6 bg-white/10 rounded-2xl hover:bg-white/20 transition-all duration-300 border border-white/20 hover:scale-105 shadow-lg">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-user-circle text-white text-2xl"></i>
                        </div>
                        <span class="text-lg font-bold text-white text-center">Profile</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection