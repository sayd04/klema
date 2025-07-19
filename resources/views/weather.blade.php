@extends('layouts.app')

@section('title', 'Weather Monitoring')

@section('content')
<div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="glass-card rounded-2xl overflow-hidden mb-8">
            <div class="gradient-bg p-6 text-white">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Weather Monitoring</h1>
                        <p class="text-green-100 mt-1">Track weather conditions for your farm locations</p>
                    </div>
                    <button onclick="document.getElementById('addLocationModal').classList.remove('hidden')" 
                            class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-white text-green-600 rounded-xl hover:bg-green-50 shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Add Location
                    </button>
                </div>
            </div>
        </div>

        @if($locations->count() > 0)
        <!-- Location Selector -->
        <div class="glass-card rounded-2xl p-6 mb-8">
            <form id="locationForm" class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-4">
                @csrf
                <label for="location_id" class="block text-sm font-medium text-gray-700 flex-shrink-0">Select Location:</label>
                <select name="location_id" id="location_id" class="flex-1 block w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70" onchange="setDefaultLocation(this.value)">
                    @foreach($locations as $location)
                    <option value="{{ $location->id }}" {{ $selectedLocation && $selectedLocation->id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Weather Data -->
        <div id="weatherContainer">
            @if($weather)
            <div class="glass-card rounded-2xl overflow-hidden mb-8">
                <div class="gradient-bg p-6 text-white" data-weather="header">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $selectedLocation->name }}
                        </h2>
                        <span class="text-green-100">Current Weather</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Main Weather -->
                        <div class="glass-card rounded-xl p-6 text-center">
                            <div class="text-5xl font-bold text-gray-800 mb-2" data-weather="temperature">{{ round($weather['main']['temp']) }}°C</div>
                            <div class="text-lg text-gray-600 capitalize" data-weather="description">{{ $weather['weather'][0]['description'] }}</div>
                            <div class="mt-4">
                                <img src="http://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@2x.png" alt="Weather icon" class="w-20 h-20 mx-auto" data-weather="icon">
                            </div>
                        </div>
                        
                        <!-- Details -->
                        <div class="glass-card rounded-xl p-6" data-weather="details">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Feels Like:</span>
                                    <span class="font-medium text-gray-800">{{ round($weather['main']['feels_like']) }}°C</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Humidity:</span>
                                    <span class="font-medium text-gray-800">{{ $weather['main']['humidity'] }}%</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Pressure:</span>
                                    <span class="font-medium text-gray-800">{{ $weather['main']['pressure'] }} hPa</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Wind -->
                        <div class="glass-card rounded-xl p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Wind Speed:</span>
                                    <span class="font-medium text-gray-800">{{ round($weather['wind']['speed'] * 3.6) }} km/h</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Wind Direction:</span>
                                    <span class="font-medium text-gray-800">{{ $weather['wind']['deg'] }}°</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Visibility:</span>
                                    <span class="font-medium text-gray-800">{{ $weather['visibility'] / 1000 }} km</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weather Summary -->
            @if($weatherSummary)
            <div class="glass-card rounded-2xl overflow-hidden mb-8">
                <div class="gradient-bg p-6 text-white">
                    <h2 class="text-xl font-bold flex items-center">
                        <i class="fas fa-chart-line mr-2"></i>
                        30-Day Weather Summary
                    </h2>
                    <p class="text-green-100 text-sm">Based on collected weather data</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $weatherSummary['avg_temperature'] }}°C</div>
                            <div class="text-sm font-semibold text-gray-800 mt-1">Avg Temperature</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $weatherSummary['avg_humidity'] }}%</div>
                            <div class="text-sm font-semibold text-gray-800 mt-1">Avg Humidity</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $weatherSummary['rainy_days'] }} days</div>
                            <div class="text-sm font-semibold text-gray-800 mt-1">Rainy Days</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $weatherSummary['sunny_days'] }} days</div>
                            <div class="text-sm font-semibold text-gray-800 mt-1">Sunny Days</div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center text-blue-700 mb-2">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span class="font-bold text-base">Weather Pattern</span>
                        </div>
                        <p class="text-gray-800 text-sm">
                            <span class="font-semibold">Most common:</span> <strong>{{ $weatherSummary['most_common_weather'] }}</strong> | 
                            <span class="font-semibold">Range:</span> <strong>{{ $weatherSummary['min_temperature'] }}°C - {{ $weatherSummary['max_temperature'] }}°C</strong>
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- 5-Day Forecast -->
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="gradient-bg p-6 text-white">
                    <h2 class="text-xl font-bold">5-Day Forecast</h2>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <div class="flex space-x-4 pb-2" data-forecast="5day">
                            @foreach($forecast['list'] as $day)
                            <div class="flex-shrink-0 w-48 glass-card rounded-xl p-4">
                                <div class="text-center">
                                    <div class="font-medium text-gray-800 mb-2">
                                        {{ \Carbon\Carbon::createFromTimestamp($day['dt'])->format('D, M j') }}
                                    </div>
                                    <div class="my-3">
                                        <img src="http://openweathermap.org/img/wn/{{ $day['weather'][0]['icon'] }}.png" alt="Weather" class="w-12 h-12 mx-auto">
                                    </div>
                                    <div class="text-xl font-bold text-gray-800 mb-1">{{ round($day['main']['temp']) }}°C</div>
                                    <div class="text-sm text-gray-600 capitalize mb-3">{{ $day['weather'][0]['description'] }}</div>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <div>
                                            <div class="text-gray-500">High</div>
                                            <div class="font-medium">{{ round($day['main']['temp_max']) }}°</div>
                                        </div>
                                        <div>
                                            <div class="text-gray-500">Low</div>
                                            <div class="font-medium">{{ round($day['main']['temp_min']) }}°</div>
                                        </div>
                                        <div>
                                            <div class="text-gray-500">Humidity</div>
                                            <div class="font-medium">{{ $day['main']['humidity'] }}%</div>
                                        </div>
                                        <div>
                                            <div class="text-gray-500">Wind</div>
                                            <div class="font-medium">{{ round($day['wind']['speed'] * 3.6) }} km/h</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @else
        <!-- Empty State -->
        <div class="glass-card rounded-2xl p-12 text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-map-marker-alt text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No locations added yet</h3>
            <p class="text-gray-500 mb-6">Add your first location to start monitoring weather conditions</p>
            <button onclick="document.getElementById('addLocationModal').classList.remove('hidden')" 
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>
                Add Your First Location
            </button>
        </div>
        @endif
    </div>
</div>

<!-- Add Location Modal -->
<div id="addLocationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="glass-card rounded-2xl w-full max-w-md">
        <div class="gradient-bg p-6 text-white rounded-t-2xl">
            <h3 class="text-xl font-bold">Add New Location</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('weather.add-location') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Location Name</label>
                        <input type="text" name="name" id="name" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70" required>
                    </div>
                    
                    <div>
                        <button type="button" onclick="getLocation()" class="w-full flex items-center justify-center px-4 py-2 border border-blue-200 text-sm font-medium rounded-xl text-blue-700 bg-blue-50 hover:bg-blue-100 mb-2">
                            <i class="fas fa-location-arrow mr-2"></i>
                            Use My Current Location
                        </button>
                        <p class="text-xs text-gray-500 text-center">Or enter coordinates manually below</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70">
                        </div>
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70">
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="document.getElementById('addLocationModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        Save Location
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Change location handler
    document.getElementById('location_id').addEventListener('change', function() {
        const locationId = this.value;
        fetch("{{ route('weather.get') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                location_id: locationId
            })
        })
        .then(response => response.json())
        .then(data => {
            // Update the weather container with new data
            document.getElementById('weatherContainer').innerHTML = `
                <div class="glass-card rounded-2xl overflow-hidden mb-8">
                    <div class="gradient-bg p-6 text-white" data-weather="header">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                ${data.location.name}
                            </h2>
                            <span class="text-green-100">Current Weather</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Main Weather -->
                            <div class="glass-card rounded-xl p-6 text-center">
                                <div class="text-5xl font-bold text-gray-800 mb-2" data-weather="temperature">${Math.round(data.weather.main.temp)}°C</div>
                                <div class="text-lg text-gray-600 capitalize" data-weather="description">${data.weather.weather[0].description}</div>
                                <div class="mt-4">
                                    <img src="http://openweathermap.org/img/wn/${data.weather.weather[0].icon}@2x.png" alt="Weather icon" class="w-20 h-20 mx-auto" data-weather="icon">
                                </div>
                            </div>
                            
                            <!-- Details -->
                            <div class="glass-card rounded-xl p-6" data-weather="details">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Feels Like:</span>
                                        <span class="font-medium text-gray-800">${Math.round(data.weather.main.feels_like)}°C</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Humidity:</span>
                                        <span class="font-medium text-gray-800">${data.weather.main.humidity}%</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Pressure:</span>
                                        <span class="font-medium text-gray-800">${data.weather.main.pressure} hPa</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Wind -->
                            <div class="glass-card rounded-xl p-6">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Wind Speed:</span>
                                        <span class="font-medium text-gray-800">${Math.round(data.weather.wind.speed * 3.6)} km/h</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Wind Direction:</span>
                                        <span class="font-medium text-gray-800">${data.weather.wind.deg}°</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Visibility:</span>
                                        <span class="font-medium text-gray-800">${data.weather.visibility / 1000} km</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5-Day Forecast -->
                <div class="glass-card rounded-2xl overflow-hidden">
                    <div class="gradient-bg p-6 text-white">
                        <h2 class="text-xl font-bold">5-Day Forecast</h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <div class="flex space-x-4 pb-2" data-forecast="5day">
                                ${data.forecast.list.map(day => `
                                    <div class="flex-shrink-0 w-48 glass-card rounded-xl p-4">
                                        <div class="text-center">
                                            <div class="font-medium text-gray-800 mb-2">
                                                ${new Date(day.dt * 1000).toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' })}
                                            </div>
                                            <div class="my-3">
                                                <img src="http://openweathermap.org/img/wn/${day.weather[0].icon}.png" alt="Weather" class="w-12 h-12 mx-auto">
                                            </div>
                                            <div class="text-xl font-bold text-gray-800 mb-1">${Math.round(day.main.temp)}°C</div>
                                            <div class="text-sm text-gray-600 capitalize mb-3">${day.weather[0].description}</div>
                                            <div class="grid grid-cols-2 gap-2 text-xs">
                                                <div>
                                                    <div class="text-gray-500">High</div>
                                                    <div class="font-medium">${Math.round(day.main.temp_max)}°</div>
                                                </div>
                                                <div>
                                                    <div class="text-gray-500">Low</div>
                                                    <div class="font-medium">${Math.round(day.main.temp_min)}°</div>
                                                </div>
                                                <div>
                                                    <div class="text-gray-500">Humidity</div>
                                                    <div class="font-medium">${day.main.humidity}%</div>
                                                </div>
                                                <div>
                                                    <div class="text-gray-500">Wind</div>
                                                    <div class="font-medium">${Math.round(day.wind.speed * 3.6)} km/h</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
    });

    // Get current location
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude.toFixed(4);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(4);
                showToast('Location obtained successfully!', 'success');
            }, function(error) {
                showToast('Unable to retrieve your location. Please enter it manually.', 'error');
            });
        } else {
            showToast('Geolocation is not supported by your browser.', 'error');
        }
    }

    function setDefaultLocation(locationId) {
        fetch('/weather/set-default-location', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ location_id: locationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Optionally reload dashboard or weather data here
                window.location.reload();
            }
        });
    }
</script>
@endpush
@endsection