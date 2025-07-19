@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center gradient-bg">
    <div class="text-center p-8 glass-card rounded-3xl shadow-2xl max-w-md mx-auto">
        <!-- Error Icon -->
        <div class="mb-8">
            <div class="w-24 h-24 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-4xl"></i>
            </div>
        </div>

        <!-- Error Message -->
        <h1 class="text-6xl font-black text-gray-900 dark:text-white mb-4">404</h1>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Page Not Found</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            The page you're looking for doesn't exist or has been moved.
        </p>

        <!-- Action Buttons -->
        <div class="space-y-4">
            <a href="{{ route('welcome') }}" class="btn-modern inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                <i class="fas fa-home mr-2"></i>
                Go Home
            </a>
            
            <button onclick="history.back()" class="btn-modern inline-flex items-center px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Go Back
            </button>
        </div>

        <!-- Additional Help -->
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                If you believe this is an error, please contact support.
            </p>
        </div>
    </div>
</div>
@endsection 