@extends('layouts.app')

@section('title', 'Add New Activity')

@section('content')
<div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="glass-card rounded-2xl overflow-hidden mb-8">
            <div class="gradient-bg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">Add New Activity</h2>
                        <p class="text-green-100 mt-1">Record your farm activities for better management</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-plus text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="glass-card rounded-2xl p-8">
            <form action="{{ route('activities.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Title Field -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-heading text-green-600 mr-2"></i>
                        Activity Title
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="{{ old('title') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70"
                        placeholder="Enter activity title..."
                        required
                    >
                    @error('title')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Activity Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-tag text-green-600 mr-2"></i>
                        Activity Type
                    </label>
                    <select 
                        name="type" 
                        id="type" 
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70"
                        required
                    >
                        <option value="">Select activity type...</option>
                        <option value="planting" {{ old('type') == 'planting' ? 'selected' : '' }}>Planting</option>
                        <option value="watering" {{ old('type') == 'watering' ? 'selected' : '' }}>Watering</option>
                        <option value="harvesting" {{ old('type') == 'harvesting' ? 'selected' : '' }}>Harvesting</option>
                        <option value="fertilizing" {{ old('type') == 'fertilizing' ? 'selected' : '' }}>Fertilizing</option>
                        <option value="pruning" {{ old('type') == 'pruning' ? 'selected' : '' }}>Pruning</option>
                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Date Field -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-calendar-day text-green-600 mr-2"></i>
                        Activity Date (Future Only)
                    </label>
                    <input 
                        type="date" 
                        name="date" 
                        id="date" 
                        value="{{ old('date') }}"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-1">Only future dates are allowed for new activities.</p>
                    @error('date')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-align-left text-green-600 mr-2"></i>
                        Description (Optional)
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="4" 
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white bg-opacity-70 resize-none"
                        placeholder="Describe the activity details, tools used, observations, etc..."
                    >{{ old('description') }}</textarea>
                    <div class="text-right text-xs text-gray-500 mt-1">
                        <span id="charCount">0</span> characters
                    </div>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('activities.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Save Activity
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Card -->
        <div class="glass-card rounded-2xl p-6 mt-8 bg-blue-50 border border-blue-100">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-lightbulb text-blue-600 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-blue-900 mb-1">Tips for Better Activity Tracking</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Use descriptive titles for easy identification</li>
                        <li>• Only future dates are allowed for new activities</li>
                        <li>• Include weather conditions and observations</li>
                        <li>• Note tools, fertilizers, or materials used</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Character counter
document.getElementById('description').addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const type = document.getElementById('type').value;
    const date = document.getElementById('date').value;
    
    if (!title || !type || !date) {
        e.preventDefault();
        showToast('Please fill in all required fields', 'error');
        return;
    }
    
    // Check if date is in the future
    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Reset time to start of day
    
    if (selectedDate <= today) {
        e.preventDefault();
        showToast('Activity date must be in the future (after today)', 'error');
        return;
    }
});
</script>
@endsection