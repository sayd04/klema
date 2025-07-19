@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-green-900 to-emerald-900 relative overflow-hidden">
    <!-- Modern animated background with organic shapes -->
    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 w-96 h-96 bg-green-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-1/4 right-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute bottom-0 left-1/3 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl animate-pulse delay-2000"></div>
        
        <!-- Floating particles -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-20 w-2 h-2 bg-green-400 rounded-full animate-bounce"></div>
            <div class="absolute top-40 right-40 w-1 h-1 bg-emerald-400 rounded-full animate-bounce delay-500"></div>
            <div class="absolute bottom-40 left-40 w-1.5 h-1.5 bg-teal-400 rounded-full animate-bounce delay-1000"></div>
        </div>
    </div>
    
    <div class="relative z-10 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full">
            <!-- Hero Section with Large Typography -->
            <div class="text-center mb-16">
                <div class="mb-8">
                    <div class="mx-auto h-24 w-24 bg-gradient-to-br from-green-400 to-emerald-600 rounded-3xl flex items-center justify-center mb-8 shadow-2xl transform hover:scale-110 transition-all duration-500 animate-float">
                        <i class="fas fa-seedling text-white text-4xl"></i>
                    </div>
                    <h1 class="text-6xl md:text-7xl font-black text-white mb-4 tracking-tight">
                        KL<span class="bg-gradient-to-r from-green-400 to-emerald-400 bg-clip-text text-transparent">E</span>MA
                    </h1>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-200 mb-6">
                        Farm Operation Management
                    </h2>
                    <p class="text-xl text-gray-300 max-w-2xl mx-auto leading-relaxed">
                        Transform your agricultural operations with advanced weather monitoring and intelligent farm activity management
                    </p>
                </div>
                <!-- Action buttons with modern design (moved up) -->
                <div class="flex flex-col md:flex-row gap-4 justify-center mt-8">
                    <a href="{{ route('login') }}" class="flex items-center justify-center w-36 md:w-44 py-2 px-4 rounded-xl font-semibold text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-400/60 shadow-lg transition-all duration-200 text-base gap-2">
                        <i class="fas fa-sign-in-alt text-lg"></i>
                        Get Started
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center justify-center w-36 md:w-44 py-2 px-4 rounded-xl font-semibold text-white bg-white/10 border border-white/20 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-emerald-400/60 shadow-lg transition-all duration-200 text-base gap-2">
                        <i class="fas fa-user-plus text-lg"></i>
                        Create Account
                    </a>
                </div>
            </div>

            <!-- Problem Statement Section -->
            <div class="mb-16">
                <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 shadow-2xl">
                    <h3 class="text-2xl font-bold text-white mb-4 text-center">
                        The Challenge
                    </h3>
                    <p class="text-gray-300 text-center text-lg leading-relaxed">
                        Traditional farm management is time-consuming, error-prone, and lacks real-time insights. 
                        Weather uncertainty and manual tracking lead to inefficient operations and reduced yields.
                    </p>
                </div>
            </div>

            <!-- Solution Features with Modern Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div class="group bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 shadow-2xl transform hover:scale-105 transition-all duration-500 hover:bg-white/15">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-cloud-sun text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Smart Weather Monitoring</h3>
                    <p class="text-gray-300 leading-relaxed">
                        Real-time weather data integration with predictive analytics for optimal farming decisions
                    </p>
                </div>
                
                <div class="group bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 shadow-2xl transform hover:scale-105 transition-all duration-500 hover:bg-white/15">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-tasks text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Activity Management</h3>
                    <p class="text-gray-300 leading-relaxed">
                        Comprehensive tracking and organization of all farm activities with smart scheduling
                    </p>
                </div>
                
                <div class="group bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 shadow-2xl transform hover:scale-105 transition-all duration-500 hover:bg-white/15">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Intelligent Reports</h3>
                    <p class="text-gray-300 leading-relaxed">
                        AI-powered insights and analytics to optimize your farm operations and increase yields
                    </p>
                </div>
            </div>

            <!-- Social Proof Section -->
            <div class="mb-16">
                <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 shadow-2xl">
                    <h3 class="text-2xl font-bold text-white mb-6 text-center">
                        Trusted by Farmers Worldwide
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-400 mb-2">500+</div>
                            <div class="text-gray-300">Active Farms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-emerald-400 mb-2">95%</div>
                            <div class="text-gray-300">Efficiency Increase</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-teal-400 mb-2">24/7</div>
                            <div class="text-gray-300">Weather Monitoring</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trust indicators with modern styling -->
            <div class="text-center mt-12">
                <div class="flex justify-center items-center space-x-8 text-sm text-gray-400">
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt text-green-400 mr-2"></i>
                        <span>Enterprise Security</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-leaf text-emerald-400 mr-2"></i>
                        <span>Sustainable Farming</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-chart-line text-teal-400 mr-2"></i>
                        <span>Data-Driven Insights</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.animate-float {
    animation: float 4s ease-in-out infinite;
}

.backdrop-blur-xl {
    backdrop-filter: blur(24px);
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

::-webkit-scrollbar-thumb {
    background: rgba(34, 197, 94, 0.5);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(34, 197, 94, 0.7);
}
</style>
@endsection