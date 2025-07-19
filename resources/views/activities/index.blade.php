@extends('layouts.app')

@section('title', 'Farm Activities')

@section('content')
<div class="py-8 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="glass-card rounded-2xl overflow-hidden mb-8">
            <div class="gradient-bg p-6 text-white">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Farm Activities</h1>
                        <p class="text-green-100 mt-1">Track and manage all your farming operations</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('activities.create') }}" class="inline-flex items-center px-4 py-2 bg-white text-green-600 rounded-xl hover:bg-green-50 shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Add Activity
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Activities</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $activities->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tasks text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">This Month</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $activities->where('date', '>=', now()->startOfMonth())->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Most Common</p>
                        <p class="text-xl font-bold text-gray-800">
                            {{ $activities->groupBy('type')->sortByDesc(function($group) { return $group->count(); })->keys()->first() ? ucfirst($activities->groupBy('type')->sortByDesc(function($group) { return $group->count(); })->keys()->first()) : 'N/A' }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-star text-purple-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Last Activity</p>
                        <p class="text-sm font-bold text-gray-800">
                            {{ $activities->sortByDesc('date')->first() ? \Carbon\Carbon::parse($activities->sortByDesc('date')->first()->date)->diffForHumans() : 'No activities' }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-history text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="glass-card rounded-2xl p-6 mb-8">
            <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-1">
                    <div class="relative">
                        <input 
                            type="text" 
                            id="searchInput" 
                            placeholder="Search activities..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <select 
                        id="typeFilter" 
                        class="px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70"
                    >
                        <option value="">All Types</option>
                        <option value="planting">Planting</option>
                        <option value="watering">Watering</option>
                        <option value="harvesting">Harvesting</option>
                        <option value="fertilizing">Fertilizing</option>
                        <option value="pruning">Pruning</option>
                        <option value="other">Other</option>
                    </select>
                    <button 
                        id="clearFilters" 
                        class="p-2 text-gray-500 hover:text-gray-700 rounded-xl hover:bg-gray-100 transition-colors"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Activities Table -->
        <div class="glass-card rounded-2xl overflow-hidden">
            @if($activities->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Title
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="activitiesTable">
                            @foreach($activities->sortByDesc('date') as $activity)
                            <tr class="hover:bg-gray-50 transition-colors activity-row" 
                                data-type="{{ $activity->type }}" 
                                data-title="{{ strtolower($activity->title) }}" 
                                data-description="{{ strtolower($activity->description ?? '') }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($activity->date)->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($activity->date)->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $activity->type === 'planting' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $activity->type === 'watering' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $activity->type === 'harvesting' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $activity->type === 'fertilizing' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $activity->type === 'other' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst($activity->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $activity->description ? Str::limit($activity->description, 60) : 'No description' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('activities.edit', $activity->id) }}" class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this activity?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-seedling text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No activities yet</h3>
                    <p class="text-gray-500 mb-6">Start tracking your farm activities to better manage your operations.</p>
                    <a href="{{ route('activities.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Add Your First Activity
                    </a>
                </div>
            @endif
        </div>

        <!-- Activity Summary -->
        @if($activities->count() > 0)
        <div class="glass-card rounded-2xl p-6 mt-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                Activity Summary
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($activities->groupBy('type') as $type => $typeActivities)
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <div class="text-2xl font-bold text-gray-800">{{ $typeActivities->count() }}</div>
                    <div class="text-sm text-gray-600 capitalize">{{ $type }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.activity-row');
    
    rows.forEach(row => {
        const title = row.getAttribute('data-title');
        const description = row.getAttribute('data-description');
        
        if (title.includes(searchTerm) || description.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Type filter
document.getElementById('typeFilter').addEventListener('change', function() {
    const selectedType = this.value;
    const rows = document.querySelectorAll('.activity-row');
    
    rows.forEach(row => {
        const type = row.getAttribute('data-type');
        
        if (selectedType === '' || type === selectedType) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Clear filters
document.getElementById('clearFilters').addEventListener('click', function() {
    document.getElementById('searchInput').value = '';
    document.getElementById('typeFilter').value = '';
    
    const rows = document.querySelectorAll('.activity-row');
    rows.forEach(row => {
        row.style.display = '';
    });
});
</script>
@endsection