@extends('layouts.app')

@section('title', 'Manage Users - Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-slate-50 to-zinc-50 dark:from-gray-900 dark:via-slate-900 dark:to-zinc-900">
    <div class="w-full">
        <!-- Header -->
        <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-3xl p-8 mb-8 border border-white/20 shadow-2xl mx-6 backdrop-blur-xl">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between px-8">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-black text-white mb-2">
                        Manage Users 
                        <span class="text-4xl animate-float inline-block">👥</span>
                    </h1>
                    <p class="text-xl text-blue-100">User management and administration</p>
                </div>
                <div class="mt-6 lg:mt-0">
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-6 py-3 bg-white/20 text-white rounded-2xl hover:bg-white/30 transition-all duration-300 shadow-lg font-bold text-lg">
                        <i class="fas fa-plus mr-3"></i>
                        Add New User
                    </a>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl mx-6 mb-8 overflow-hidden">
            <div class="p-8">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left py-4 px-6 font-bold text-gray-700 dark:text-gray-300">Name</th>
                                <th class="text-left py-4 px-6 font-bold text-gray-700 dark:text-gray-300">Email</th>
                                <th class="text-left py-4 px-6 font-bold text-gray-700 dark:text-gray-300">Role</th>
                                <th class="text-left py-4 px-6 font-bold text-gray-700 dark:text-gray-300">Activities</th>
                                <th class="text-left py-4 px-6 font-bold text-gray-700 dark:text-gray-300">Joined</th>
                                <th class="text-left py-4 px-6 font-bold text-gray-700 dark:text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3
                                            {{ $user->role === 'admin' ? 'bg-gradient-to-br from-purple-400 to-pink-500' : 'bg-gradient-to-br from-blue-400 to-cyan-500' }}">
                                            <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : 'user' }} text-white"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ $user->full_name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-gray-600 dark:text-gray-300">{{ $user->activities_count ?? 0 }}</td>
                                <td class="py-4 px-6 text-gray-600 dark:text-gray-300">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 px-6 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
                                        <p class="text-lg font-semibold mb-2">No users found</p>
                                        <p class="text-sm">Get started by adding your first user</p>
                                        <a href="{{ route('admin.users.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-plus mr-2"></i>
                                            Add User
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mx-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-3xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-100">Total Users</p>
                        <p class="text-3xl font-black">{{ $users->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-3xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-100">Admins</p>
                        <p class="text-3xl font-black">{{ $users->where('role', 'admin')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-user-shield text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-100">Regular Users</p>
                        <p class="text-3xl font-black">{{ $users->where('role', 'user')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-user text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 