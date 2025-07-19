<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KLEMA Farm Management')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #16a34a;
            --primary-dark: #15803d;
            --secondary: #2563eb;
            --accent: #7c3aed;
            --light: #f8fafc;
            --dark: #1e293b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }
        
        * {
            scroll-behavior: smooth;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #1e293b;
            transition: all 0.3s ease;
        }
        
        .dark body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #f1f5f9;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .dark .glass-card {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .glass-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px 0 rgba(31, 38, 135, 0.15);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        }
        
        .sidebar {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        
        .dark .sidebar {
            background: rgba(15, 23, 42, 0.95);
        }
        
        .toast {
            animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards, fadeOut 0.5s 2.5s forwards;
        }
        
        @keyframes slideIn {
            from { 
                transform: translateX(100%) scale(0.9);
                opacity: 0;
            }
            to { 
                transform: translateX(0) scale(1);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.9); }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(1deg); }
        }
        
        .nav-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .nav-item:hover::before {
            left: 100%;
        }
        
        .nav-item.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            transform: translateX(8px);
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        }
        
        .nav-item.active i {
            color: white !important;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #166534 100%);
        }
        
        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Micro-interactions */
        .btn-modern {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .btn-modern::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-modern:hover::before {
            width: 300px;
            height: 300px;
        }
    </style>
</head>
<body class="min-h-screen">
    <div id="app">
        @auth
        <!-- Mobile menu button with modern design -->
        <button id="mobile-menu-button" class="md:hidden fixed top-4 left-4 z-50 p-3 rounded-2xl glass-card border border-white/20 shadow-2xl hover:scale-110 transition-all duration-300">
            <i class="fas fa-bars text-gray-800 dark:text-white"></i>
        </button>

        <!-- Modern Sidebar -->
        <div id="sidebar" class="sidebar fixed inset-y-0 left-0 w-72 shadow-2xl transform -translate-x-full md:translate-x-0 z-40">
            <div class="gradient-bg text-white p-8">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-seedling text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black tracking-tight">KLEMA</h1>
                        <p class="text-sm text-green-100 font-medium">Farm Management</p>
                    </div>
                </div>
            </div>
            
            <!-- User Profile with modern design -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-4">
                    @if(Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile" class="w-12 h-12 rounded-2xl object-cover ring-2 ring-green-500/20">
                    @else
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-sm">{{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Modern Navigation -->
            <nav class="p-6 space-y-2">
                <a href="{{ route('dashboard') }}" class="nav-item flex items-center px-4 py-3 text-sm font-semibold rounded-2xl {{ request()->routeIs('dashboard') ? 'active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <i class="fas fa-tachometer-alt mr-4 text-lg {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400' }}"></i>
                    Dashboard
                </a>
                <a href="{{ route('activities.index') }}" class="nav-item flex items-center px-4 py-3 text-sm font-semibold rounded-2xl {{ request()->routeIs('activities.*') ? 'active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <i class="fas fa-tasks mr-4 text-lg {{ request()->routeIs('activities.*') ? 'text-white' : 'text-gray-400' }}"></i>
                    Activities
                </a>
                <a href="{{ route('weather') }}" class="nav-item flex items-center px-4 py-3 text-sm font-semibold rounded-2xl {{ request()->routeIs('weather') ? 'active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <i class="fas fa-cloud-sun mr-4 text-lg {{ request()->routeIs('weather') ? 'text-white' : 'text-gray-400' }}"></i>
                    Weather
                </a>
                <a href="{{ route('reports') }}" class="nav-item flex items-center px-4 py-3 text-sm font-semibold rounded-2xl {{ request()->routeIs('reports') ? 'active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <i class="fas fa-chart-bar mr-4 text-lg {{ request()->routeIs('reports') ? 'text-white' : 'text-gray-400' }}"></i>
                    Reports
                </a>
                <a href="{{ route('profile') }}" class="nav-item flex items-center px-4 py-3 text-sm font-semibold rounded-2xl {{ request()->routeIs('profile') ? 'active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <i class="fas fa-user-circle mr-4 text-lg {{ request()->routeIs('profile') ? 'text-white' : 'text-gray-400' }}"></i>
                    Profile
                </a>
                
                @if(Auth::user()->isAdmin())
                <!-- Admin Section -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Admin Panel
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center px-4 py-3 text-sm font-semibold rounded-2xl {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <i class="fas fa-shield-alt mr-4 text-lg {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400' }}"></i>
                        Admin Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-item flex items-center px-4 py-3 text-sm font-semibold rounded-2xl {{ request()->routeIs('admin.users.*') ? 'active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <i class="fas fa-users mr-4 text-lg {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        Manage Users
                    </a>
                    <a href="{{ route('admin.stats') }}" class="nav-item flex items-center px-4 py-3 text-sm font-semibold rounded-2xl {{ request()->routeIs('admin.stats') ? 'active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <i class="fas fa-chart-line mr-4 text-lg {{ request()->routeIs('admin.stats') ? 'text-white' : 'text-gray-400' }}"></i>
                        System Stats
                    </a>
                </div>
                @endif
            </nav>
            
            <!-- Modern Logout -->
            <div class="absolute bottom-0 left-0 right-0 p-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-2xl transition-all duration-300 btn-modern">
                        <i class="fas fa-sign-out-alt mr-4 text-lg"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Mobile overlay with blur -->
        <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 hidden md:hidden"></div>

        <!-- Main content with modern spacing -->
        <div class="md:ml-72 min-h-screen p-6">
            @yield('content')
        </div>
        @else
            @yield('content')
        @endauth
    </div>

    <!-- Modern Toast notifications -->
    <div id="toast-container" class="fixed top-6 right-6 z-50 space-y-3"></div>

    @stack('scripts')
    
    <script>
        // Enhanced mobile menu toggle with smooth animations
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            
            // Add haptic feedback for mobile
            if (navigator.vibrate) {
                navigator.vibrate(50);
            }
        });

        // Close mobile menu when clicking overlay
        document.getElementById('mobile-menu-overlay')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');
            
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        // Enhanced toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const icons = {
                success: 'check-circle',
                error: 'exclamation-circle',
                warning: 'exclamation-triangle',
                info: 'info-circle'
            };
            
            const colors = {
                success: 'bg-gradient-to-r from-green-500 to-emerald-600',
                error: 'bg-gradient-to-r from-red-500 to-pink-600',
                warning: 'bg-gradient-to-r from-yellow-500 to-orange-600',
                info: 'bg-gradient-to-r from-blue-500 to-cyan-600'
            };
            
            toast.className = `toast p-4 rounded-2xl shadow-2xl flex items-center text-white backdrop-blur-xl border border-white/20 ${colors[type]}`;
            toast.innerHTML = `
                <i class="fas fa-${icons[type]} mr-3 text-lg"></i>
                <span class="font-medium">${message}</span>
            `;
            
            document.getElementById('toast-container').appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 4000);
        }

        // Show Laravel flash messages as enhanced toasts
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
        
        @if(session('warning'))
            showToast("{{ session('warning') }}", 'warning');
        @endif
        
        @if(session('info'))
            showToast("{{ session('info') }}", 'info');
        @endif

        // Show validation errors as toasts
        @if(isset($errors) && $errors->any())
            @foreach($errors->all() as $error)
                showToast("{{ $error }}", 'error');
            @endforeach
        @endif

        // Add smooth scroll behavior
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading states
            const links = document.querySelectorAll('a[href]');
            links.forEach(link => {
                link.addEventListener('click', function() {
                    if (this.href && !this.href.includes('#')) {
                        this.style.opacity = '0.7';
                        this.style.transform = 'scale(0.95)';
                    }
                });
            });
        });
    </script>

    <!-- Real-time Weather Updates -->
    <script>
// Real-time Weather Updates
class WeatherUpdater {
    constructor() {
        this.isWeatherPage = window.location.pathname === '/weather';
        this.isDashboard = window.location.pathname === '/dashboard';
        this.updateInterval = 300000; // 5 minutes
        this.init();
    }

    init() {
        // Update weather immediately
        this.updateWeather();
        
        // Set up periodic updates
        setInterval(() => {
            this.updateWeather();
        }, this.updateInterval);
        
        // Add refresh buttons
        this.addRefreshButtons();
    }

    async updateWeather() {
        try {
            let response;
            
            if (this.isWeatherPage) {
                // For weather page, get current location's weather
                const locationSelect = document.querySelector('select[name="location_id"]');
                if (!locationSelect || !locationSelect.value) return;
                
                response = await fetch(`/api/weather/current/${locationSelect.value}`);
            } else if (this.isDashboard) {
                // For dashboard, get dashboard weather data
                response = await fetch('/api/weather/dashboard');
            } else {
                return;
            }
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                // Update current weather stats
                this.updateWeatherUI(data);
                
                // Update dashboard forecast if on dashboard
                if (this.isDashboard && data.forecast) {
                    this.updateDashboardForecast(data.forecast);
                }
                
                // Update weather page forecast if on weather page
                if (this.isWeatherPage && data.forecast) {
                    this.updateForecastUI(data);
                }
            }
        } catch (error) {
            console.error('Weather update failed:', error);
        }
    }

    updateWeatherUI(data) {
        // Update current weather stats
        if (data.weather) {
            const humidityElement = document.querySelector('[data-weather="humidity"]');
            const windElement = document.querySelector('[data-weather="wind"]');
            const tempElement = document.querySelector('[data-weather="temp"]');
            const descriptionElement = document.querySelector('[data-weather="description"]');
            const iconElement = document.querySelector('[data-weather="icon"]');
            
            if (humidityElement) {
                humidityElement.textContent = `${data.weather.main.humidity}%`;
            }
            
            if (windElement) {
                windElement.textContent = `${Math.round(data.weather.wind.speed * 3.6)} km/h`;
            }
            
            if (tempElement) {
                tempElement.textContent = `${Math.round(data.weather.main.temp)}°C`;
            }
            
            if (descriptionElement) {
                descriptionElement.textContent = data.weather.weather[0].description;
            }
            
            if (iconElement) {
                iconElement.src = `http://openweathermap.org/img/wn/${data.weather.weather[0].icon}@2x.png`;
            }
        }

        // Update weather details if on weather page
        if (this.isWeatherPage && data.weather) {
            this.updateWeatherDetails(data.weather);
        }
    }

    updateDashboardForecast(forecast) {
        const forecastContainer = document.querySelector('[data-forecast="7day"]');
        if (!forecastContainer || !forecast.list) return;

        const forecastHTML = forecast.list.map((day, index) => {
            const date = new Date(day.dt * 1000);
            const dayName = index === 0 ? 'Today' : date.toLocaleDateString('en-US', { weekday: 'short' });
            
            return `
                <div class="flex-shrink-0 flex flex-col items-center justify-center bg-white/10 rounded-lg group hover:scale-105 transition-transform duration-200 hover:drop-shadow-md px-1 py-1 min-w-[48px] max-w-[56px]">
                    <div class="text-[10px] font-bold text-white mb-0.5 text-center truncate">
                        ${dayName}
                    </div>
                    <img src="http://openweathermap.org/img/wn/${day.weather[0].icon}.png" alt="Weather" class="w-5 h-5 mb-0.5">
                    <div class="text-xs font-black text-white">${Math.round(day.main.temp)}°C</div>
                    <div class="text-[9px] text-green-100 capitalize font-medium text-center truncate">${day.weather[0].description}</div>
                </div>
            `;
        }).join('');

        forecastContainer.innerHTML = forecastHTML;
    }

    updateForecastUI(data) {
        const forecastContainer = document.querySelector('[data-forecast="5day"]');
        if (!forecastContainer || !data.forecast.list) return;

        const forecastHTML = data.forecast.list.map(day => {
            const date = new Date(day.dt * 1000);
            const formattedDate = date.toLocaleDateString('en-US', { 
                weekday: 'short', 
                month: 'short', 
                day: 'numeric' 
            });
            
            return `
                <div class="flex-shrink-0 w-48 glass-card rounded-xl p-4">
                    <div class="text-center">
                        <div class="font-medium text-gray-800 mb-2">${formattedDate}</div>
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
            `;
        }).join('');

        forecastContainer.innerHTML = forecastHTML;
    }

    updateWeatherDetails(weather) {
        const detailsContainer = document.querySelector('[data-weather="details"]');
        if (!detailsContainer) return;

        const detailsHTML = `
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Feels Like:</span>
                    <span class="font-medium text-gray-800">${Math.round(weather.main.feels_like)}°C</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Humidity:</span>
                    <span class="font-medium text-gray-800">${weather.main.humidity}%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Pressure:</span>
                    <span class="font-medium text-gray-800">${weather.main.pressure} hPa</span>
                </div>
            </div>
        `;

        detailsContainer.innerHTML = detailsHTML;
    }

    updateTimestamp(timestamp) {
        const timestampElements = document.querySelectorAll('[data-weather="timestamp"]');
        const time = new Date(timestamp).toLocaleTimeString();
        
        timestampElements.forEach(element => {
            element.textContent = `Last updated: ${time}`;
        });
    }

    addRefreshButtons() {
        // Add refresh button to weather page
        if (this.isWeatherPage) {
            const weatherHeader = document.querySelector('[data-weather="header"]');
            if (weatherHeader) {
                const refreshBtn = document.createElement('button');
                refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i>';
                refreshBtn.className = 'ml-2 p-2 text-green-100 hover:text-white transition-colors';
                refreshBtn.title = 'Refresh weather data';
                refreshBtn.onclick = () => this.updateWeather();
                
                weatherHeader.appendChild(refreshBtn);
            }
        }

        // Add refresh button to dashboard
        if (this.isDashboard) {
            const dashboardHeader = document.querySelector('[data-dashboard="header"]');
            if (dashboardHeader) {
                const refreshBtn = document.createElement('button');
                refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i>';
                refreshBtn.className = 'ml-2 p-2 text-green-100 hover:text-white transition-colors';
                refreshBtn.title = 'Refresh weather data';
                refreshBtn.onclick = () => this.updateWeather();
                
                dashboardHeader.appendChild(refreshBtn);
            }
        }
    }
}

// Real-time Clock
class ClockUpdater {
    constructor() {
        this.init();
    }

    init() {
        // Update clock immediately
        this.updateClock();
        
        // Update clock every second
        setInterval(() => {
            this.updateClock();
        }, 1000);
    }

    updateClock() {
        // Get current time in Philippine timezone
        const now = new Date();
        const philippineTime = new Date(now.toLocaleString("en-US", {timeZone: "Asia/Manila"}));
        
        // Format time as 12-hour format with AM/PM
        const timeString = philippineTime.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });

        // Update all clock elements
        const clockElements = document.querySelectorAll('[data-clock="time"]');
        clockElements.forEach(element => {
            element.textContent = timeString;
        });

        // Update date elements
        const dateElements = document.querySelectorAll('[data-clock="date"]');
        dateElements.forEach(element => {
            const dateString = philippineTime.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            element.textContent = dateString;
        });
    }
}

// Initialize weather updater and clock updater when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new WeatherUpdater();
    new ClockUpdater();
});

// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const sidebar = document.getElementById('sidebar');
    
    if (mobileMenuButton && sidebar) {
        mobileMenuButton.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
        });
    }
});
    </script>
</body>
</html>