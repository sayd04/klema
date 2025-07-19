@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Reports & Analytics</h1>
            <p class="text-gray-600">Track your farm's performance and generate insights</p>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Activities</p>
                        <p class="text-2xl font-bold text-gray-900">147</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up"></i> +12% from last month
                        </p>
                    </div>
                    <div class="bg-blue-50 rounded-full p-3">
                        <i class="fas fa-tasks text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Completed Tasks</p>
                        <p class="text-2xl font-bold text-gray-900">89</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up"></i> +8% from last month
                        </p>
                    </div>
                    <div class="bg-green-50 rounded-full p-3">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending Tasks</p>
                        <p class="text-2xl font-bold text-gray-900">23</p>
                        <p class="text-xs text-yellow-600 mt-1">
                            <i class="fas fa-clock"></i> Due this week
                        </p>
                    </div>
                    <div class="bg-yellow-50 rounded-full p-3">
                        <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Efficiency</p>
                        <p class="text-2xl font-bold text-gray-900">94%</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up"></i> +5% from last month
                        </p>
                    </div>
                    <div class="bg-purple-50 rounded-full p-3">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Activity Performance Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Activity Performance</h2>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">7 Days</button>
                        <button class="px-3 py-1 text-xs font-medium text-gray-500 hover:bg-gray-100 rounded-full">30 Days</button>
                        <button class="px-3 py-1 text-xs font-medium text-gray-500 hover:bg-gray-100 rounded-full">90 Days</button>
                    </div>
                </div>
                <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                    <div class="text-center">
                        <i class="fas fa-chart-bar text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">Chart will be displayed here</p>
                        <p class="text-sm text-gray-400 mt-2">Connect your data source to see analytics</p>
                    </div>
                </div>
            </div>

            <!-- Weather Impact -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Weather Impact</h2>
                    <i class="fas fa-cloud-sun text-blue-500 text-xl"></i>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-sun text-yellow-500 mr-3"></i>
                            <span class="text-sm font-medium">Sunny Days</span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">18 days</p>
                            <p class="text-xs text-green-600">+15% productivity</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-cloud-rain text-blue-500 mr-3"></i>
                            <span class="text-sm font-medium">Rainy Days</span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">8 days</p>
                            <p class="text-xs text-red-600">-5% productivity</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-wind text-gray-500 mr-3"></i>
                            <span class="text-sm font-medium">Windy Days</span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">4 days</p>
                            <p class="text-xs text-yellow-600">No impact</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Log -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Recent Activity Log</h2>
                <button class="text-green-600 hover:text-green-700 text-sm font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-seedling text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Seed Planting - Field A</p>
                            <p class="text-xs text-gray-500">Completed 2 hours ago</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Completed</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-tint text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Irrigation System Check</p>
                            <p class="text-xs text-gray-500">Scheduled for tomorrow</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">Scheduled</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-bug text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Pest Control - Field B</p>
                            <p class="text-xs text-gray-500">In progress</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">In Progress</span>
                </div>
            </div>
        </div>

        <!-- Export Options -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Export Reports</h2>
            
            <!-- Date Range Filter -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Date Range (Required)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Start Date *</label>
                        <input type="date" id="export_start_date" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">End Date *</label>
                        <input type="date" id="export_end_date" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">* Both start and end dates are required for export</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Activities Export -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-tasks text-green-600 mr-2"></i>
                        Activities Export
                    </h3>
                    <button onclick="exportData('activities', 'excel')" class="flex items-center justify-center px-4 py-2 bg-green-50 border border-green-200 rounded-lg text-green-700 hover:bg-green-100 transition-colors duration-200 text-sm w-full">
                        <i class="fas fa-file-excel mr-2"></i>
                        Export to Excel
                    </button>
                </div>

                <!-- Weather Export -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-cloud-sun text-blue-600 mr-2"></i>
                        Weather Data Export
                    </h3>
                    <button onclick="exportData('weather', 'excel')" class="flex items-center justify-center px-4 py-2 bg-blue-50 border border-blue-200 rounded-lg text-blue-700 hover:bg-blue-100 transition-colors duration-200 text-sm w-full">
                        <i class="fas fa-file-excel mr-2"></i>
                        Export to Excel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Chart data simulation
    const chartData = {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Activities Completed',
            data: [12, 19, 3, 5, 2, 3, 8],
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            borderColor: 'rgba(34, 197, 94, 1)',
            borderWidth: 2
        }]
    };

    // Export functionality
    function exportData(type, format) {
        const startDate = document.getElementById('export_start_date').value;
        const endDate = document.getElementById('export_end_date').value;
        
        // Validate that both dates are set
        if (!startDate || !endDate) {
            showToast('Please select both start and end dates before exporting', 'error');
            return;
        }
        
        // Validate date range
        if (startDate > endDate) {
            showToast('Start date cannot be after end date', 'error');
            return;
        }
        
        // Show loading state
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
        button.disabled = true;
        
        // Create form data
        const formData = new FormData();
        formData.append('format', format);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('start_date', startDate);
        formData.append('end_date', endDate);
        
        // Determine the endpoint based on type
        const endpoint = type === 'activities' ? '{{ route("reports.export.activities") }}' : '{{ route("reports.export.weather") }}';
        
        fetch(endpoint, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error('Export failed');
        })
        .then(blob => {
            // Create download link
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${type}_${format}_${new Date().toISOString().split('T')[0]}.xlsx`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            showToast(`${type} exported successfully to Excel`, 'success');
        })
        .catch(error => {
            console.error('Export error:', error);
            showToast('Export failed. Please try again.', 'error');
        })
        .finally(() => {
            // Restore button state
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
    
    // Time period filter
    document.querySelectorAll('.flex.space-x-2 button').forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            this.parentElement.querySelectorAll('button').forEach(btn => {
                btn.classList.remove('bg-green-100', 'text-green-700');
                btn.classList.add('text-gray-500', 'hover:bg-gray-100');
            });
            
            // Add active class to clicked button
            this.classList.remove('text-gray-500', 'hover:bg-gray-100');
            this.classList.add('bg-green-100', 'text-green-700');
            
            showToast(`Switched to ${this.textContent} view`, 'success');
        });
    });
</script>
@endpush
@endsection