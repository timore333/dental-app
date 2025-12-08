@props(['label', 'value', 'icon' => 'trending-up', 'color' => 'bg-blue-500', 'trend' => null])

<div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition">
    <div class="flex justify-between items-start mb-4">
        <div>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ $label }}</p>
            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ $value }}</p>
        </div>
        <div class="{{ $color }} p-3 rounded-lg">
            @if($icon === 'users')
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            @elseif($icon === 'calendar')
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            @elseif($icon === 'trending-up')
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            @elseif($icon === 'alert-circle')
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            @elseif($icon === 'check-circle')
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            @elseif($icon === 'credit-card')
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h.01M11 15h.01M15 15h.01M4 5h16a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                </svg>
            @elseif($icon === 'clock')
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            @else
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            @endif
        </div>
    </div>

    @if($trend)
    <div class="text-sm">
        <span class="text-{{ $trend > 0 ? 'green' : 'red' }}-600 dark:text-{{ $trend > 0 ? 'green' : 'red' }}-400 font-semibold">
            {{ $trend > 0 ? '↑' : '↓' }} {{ abs($trend) }}%
        </span>
        <span class="text-slate-600 dark:text-slate-400">from last period</span>
    </div>
    @endif
</div>
