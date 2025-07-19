@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-teal-50">
    <div class="relative max-w-md w-full px-4 py-12">
        <div class="text-center mb-10">
            <div class="mx-auto w-20 h-20 bg-gradient-to-br from-green-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg mb-6 animate-float">
                <i class="fas fa-unlock-alt text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Forgot Password</h1>
            <p class="text-gray-600">Enter your email to receive a password reset link.</p>
        </div>
        <div class="glass-card rounded-2xl p-8 shadow-xl">
            @if (session('status'))
                <div class="mb-4 font-medium text-green-600">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                    <input id="email" type="email" name="email" required autofocus class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70" placeholder="your@email.com" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-green-500 shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    Send Password Reset Link
                </button>
                <div class="text-center text-sm text-gray-600 mt-6">
                    <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-500">Back to login</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 