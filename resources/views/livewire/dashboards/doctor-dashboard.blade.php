<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('Doctor Dashboard') }}</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ __('Your Schedule & Performance') }}</p>
            </div>
            <button
                wire:click="toggleTodayFilter"
                class="px-4 py-2 rounded-lg font-medium transition {{ $todayOnly ? 'bg-teal-500 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100' }}"
            >
                {{ __('Today Only') }}
            </button>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <x-metric-card
                label="{{ __('Today\'s Appointments') }}"
                :value="$metrics['today_appointments']"
                icon="calendar"
                color="bg-blue-500"
            />
            <x-metric-card
                label="{{ __('Completed Visits') }}"
                :value="$metrics['completed_visits']"
                icon="check-circle"
                color="bg-green-500"
            />
            <x-metric-card
                label="{{ __('Pending') }}"
                :value="$metrics['pending_appointments']"
                icon="clock"
                color="bg-yellow-500"
            />
            <x-metric-card
                label="{{ __('Earnings') }}"
                :value="'$' . number_format($metrics['total_earnings'], 2)"
                icon="trending-up"
                color="bg-purple-500"
            />
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Today\'s Appointments') }}</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b dark:border-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Time') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Patient') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Type') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Status') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                        <tr class="border-b dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                            <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">{{ $appointment->appointment_date->format('H:i') }}</td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $appointment->patient->name }}</td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $appointment->appointment_type->name ?? 'General' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($appointment->status === 'completed') bg-green-100 text-green-700
                                    @elseif($appointment->status === 'scheduled') bg-blue-100 text-blue-700
                                    @else bg-yellow-100 text-yellow-700
                                    @endif
                                ">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    @if($appointment->status !== 'completed')
                                    <button
                                        wire:click="recordVisit({{ $appointment->id }})"
                                        class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs font-medium transition"
                                    >
                                        {{ __('Record Visit') }}
                                    </button>
                                    <button
                                        wire:click="completeAppointment({{ $appointment->id }})"
                                        class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-xs font-medium transition"
                                    >
                                        {{ __('Complete') }}
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                {{ __('No appointments scheduled') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Visits -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Recent Visits') }}</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b dark:border-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Patient') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Procedures') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Date') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Notes') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visits as $visit)
                        <tr class="border-b dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                            <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">{{ $visit->patient->name }}</td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">
                                @foreach($visit->procedures as $procedure)
                                    <span class="inline-block px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 rounded text-xs mr-1 mb-1">
                                        {{ $procedure->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $visit->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300 text-xs">
                                {{ Str::limit($visit->notes, 50) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                {{ __('No visits recorded') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
