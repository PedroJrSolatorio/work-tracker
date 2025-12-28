<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <x-stat-card 
        label="Total Days Tracked" 
        :value="$stats['total_days']"
        color="blue" 
    />
    
    <x-stat-card 
        label="Days Completed" 
        :value="$stats['completed_days']"
        color="green" 
    />
    
    <x-stat-card 
        label="Total Hours" 
        :value="$stats['total_hours_worked']"
        color="purple" 
    />
    
    <x-stat-card 
        label="Avg Hours/Day" 
        :value="$stats['avg_hours_per_day']"
        color="orange" 
    />
    
    <div class="bg-white rounded-lg shadow-lg p-6 text-center">
        <p class="text-sm text-gray-600 mb-2">Completion Rate</p>
        <p class="text-3xl font-bold text-pink-600">{{ $stats['completion_rate'] }}%</p>
    </div>
</div>