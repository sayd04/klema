@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="glass-card rounded-2xl overflow-hidden mb-8">
            <div class="gradient-bg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Reports & Analytics</h1>
                        <p class="text-green-100 mt-1">Track your farm's performance and generate insights</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Filter -->
        <div class="glass-card rounded-2xl p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-filter text-green-600 mr-3"></i>
                Report Filters
            </h2>
            
            <form action="{{ route('reports.generate') }}" method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-calendar-start text-green-500 mr-2"></i>
                            Start Date
                        </label>
                        <input 
                            type="date" 
                            name="start_date" 
                            id="start_date" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70" 
                            value="{{ request('start_date') ?? now()->startOfMonth()->format('Y-m-d') }}"
                        >
                    </div>
                    
                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-calendar-end text-green-500 mr-2"></i>
                            End Date
                        </label>
                        <input 
                            type="date" 
                            name="end_date" 
                            id="end_date" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70" 
                            value="{{ request('end_date') ?? now()->endOfMonth()->format('Y-m-d') }}"
                        >
                    </div>
                    
                    <!-- Generate Button -->
                    <div class="flex items-end">
                        <button 
                            type="submit" 
                            class="w-full flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                        >
                            <i class="fas fa-chart-bar mr-2"></i>
                            Generate Report
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Report Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Activities by Date -->
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="gradient-bg p-6 text-white">
                    <h3 class="text-xl font-bold flex items-center">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Activities by Date
                    </h3>
                </div>
                <div class="p-6">
                    @if(isset($activities) && $activities->count() > 0)
                        <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                            @foreach($activities as $date => $dailyActivities)
                                <div class="glass-card rounded-xl p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="font-medium text-gray-800 flex items-center">
                                            <i class="fas fa-calendar-day text-blue-500 mr-2"></i>
                                            {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                                        </h4>
                                        <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                            {{ count($dailyActivities) }} {{ count($dailyActivities) === 1 ? 'activity' : 'activities' }}
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        @foreach($dailyActivities as $activity)
                                            <div class="border-l-4 border-green-500 pl-3 py-2">
                                                <div class="text-sm font-medium text-gray-800">{{ $activity->title }}</div>
                                                <div class="text-xs text-gray-500 flex items-center mt-1">
                                                    <i class="fas fa-tag mr-1"></i>
                                                    {{ $activity->type }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
                            </div>
                            <p class="text-gray-500 text-lg">No activities found for the selected period</p>
                            <p class="text-gray-400 text-sm mt-2">Try selecting a different date range</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Activity Types Distribution -->
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="gradient-bg p-6 text-white">
                    <h3 class="text-xl font-bold flex items-center">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Activity Types Distribution
                    </h3>
                </div>
                <div class="p-6">
                    @if(isset($activityTypes) && $activityTypes->count() > 0)
                        <div class="relative h-64">
                            <canvas id="activityChart"></canvas>
                        </div>
                        
                        <!-- Legend -->
                        <div class="mt-6 grid grid-cols-2 gap-3">
                            @foreach($activityTypes as $index => $type)
                                <div class="flex items-center space-x-2 text-sm">
                                    <div class="w-4 h-4 rounded-full" style="background-color: {{ ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'][$index % 6] }}"></div>
                                    <span class="text-gray-700">{{ $type->type }}</span>
                                    <span class="text-gray-500">({{ $type->count }})</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-chart-pie text-gray-400 text-3xl"></i>
                            </div>
                            <p class="text-gray-500 text-lg">No activity data available</p>
                            <p class="text-gray-400 text-sm mt-2">Add some activities to see the distribution</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        @if(isset($activities) && $activities->count() > 0)
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="gradient-bg p-6 text-white">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Report Summary
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="glass-card rounded-xl p-6 text-center bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                        <div class="text-3xl font-bold">{{ $activities->flatten()->count() }}</div>
                        <div class="text-sm">Total Activities</div>
                    </div>
                    
                    <div class="glass-card rounded-xl p-6 text-center bg-gradient-to-r from-green-500 to-green-600 text-white">
                        <div class="text-3xl font-bold">{{ $activities->count() }}</div>
                        <div class="text-sm">Active Days</div>
                    </div>
                    
                    <div class="glass-card rounded-xl p-6 text-center bg-gradient-to-r from-purple-500 to-purple-600 text-white">
                        <div class="text-3xl font-bold">{{ $activityTypes->count() ?? 0 }}</div>
                        <div class="text-sm">Activity Types</div>
                    </div>
                    
                    <div class="glass-card rounded-xl p-6 text-center bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                        <div class="text-3xl font-bold">{{ number_format($activities->flatten()->count() / max($activities->count(), 1), 1) }}</div>
                        <div class="text-sm">Avg per Day</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Export Options -->
        <div class="glass-card rounded-2xl p-6 mt-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-download text-green-600 mr-3"></i>
                Export Reports
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button class="flex items-center justify-center px-4 py-3 border border-green-200 text-sm font-medium rounded-xl text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                    <i class="fas fa-file-excel mr-2"></i>
                    Export to Excel
                </button>
                <button class="flex items-center justify-center px-4 py-3 border border-red-200 text-sm font-medium rounded-xl text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Export to PDF
                </button>
                <button class="flex items-center justify-center px-4 py-3 border border-blue-200 text-sm font-medium rounded-xl text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                    <i class="fas fa-file-csv mr-2"></i>
                    Export to CSV
                </button>
            </div>
        </div>
    </div>
</div>

@if(isset($activityTypes) && $activityTypes->count() > 0)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('activityChart').getContext('2d');
    const activityChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($activityTypes->pluck('type')) !!},
            datasets: [{
                data: {!! json_encode($activityTypes->pluck('count')) !!},
                backgroundColor: [
                    '#10B981',
                    '#3B82F6',
                    '#F59E0B',
                    '#EF4444',
                    '#8B5CF6',
                    '#EC4899'
                ],
                borderWidth: 0,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#374151',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '65%'
        }
    });

    // Export functionality
    document.querySelectorAll('button').forEach(button => {
        if (button.textContent.includes('Export')) {
            button.addEventListener('click', function() {
                const type = this.textContent.trim();
                showToast(`${type} functionality will be implemented soon`, 'info');
            });
        }
    });
</script>
@endpush
@endif
@endsection