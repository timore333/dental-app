<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('Receptionist Dashboard') }}</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ __('Manage Appointments & Payments') }}</p>
            </div>
            <button
                wire:click="createAppointment"
                class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg font-medium transition"
            >
                + {{ __('New Appointment') }}
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
                label="{{ __('Pending Insurance') }}"
                :value="$metrics['pending_insurance']"
                icon="alert-circle"
                color="bg-orange-500"
            />
            <x-metric-card
                label="{{ __('Unpaid Bills') }}"
                :value="$metrics['unpaid_bills']"
                icon="credit-card"
                color="bg-red-500"
            />
            <x-metric-card
                label="{{ __('Completed Today') }}"
                :value="$metrics['completed_today']"
                icon="check-circle"
                color="bg-green-500"
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
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Doctor') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                        <tr class="border-b dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                            <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">{{ $appointment->appointment_date->format('H:i') }}</td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $appointment->patient->name }}</td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $appointment->doctor->name }}</td>
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                {{ __('No appointments for today') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Pending Insurance Requests -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Pending Insurance Requests') }}</h2>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($pendingInsurance as $request)
                    <div class="p-3 border dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $request->appointment->patient->name }}</p>
                                <p class="text-xs text-slate-600 dark:text-slate-400">{{ $request->insuranceCompany->name }}</p>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Amount: ${{ number_format($request->estimated_cost, 2) }}</p>
                            </div>
                            <a href="{{ route('insurance-requests.show', $request->id) }}" class="text-blue-500 hover:text-blue-700 text-xs font-medium">
                                {{ __('View') }}
                            </a>
                        </div>
                    </div>
                    @empty
                    <p class="text-slate-500 dark:text-slate-400 text-center py-4">{{ __('No pending insurance requests') }}</p>
                    @endforelse
                </div>
            </div>

            <!-- Overdue Bills -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Overdue Bills') }}</h2>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($overdueBills as $bill)
                    <div class="p-3 border dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition bg-red-50 dark:bg-red-900/20">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $bill->patient->name }}</p>
                                <p class="text-xs text-slate-600 dark:text-slate-400">Due: ${{ number_format($bill->balance, 2) }}</p>
                                <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ __('Overdue') }}</p>
                            </div>
                            <button
                                wire:click="processPayment({{ $bill->id }})"
                                class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-xs font-medium transition"
                            >
                                {{ __('Pay') }}
                            </button>
                        </div>
                    </div>
                    @empty
                    <p class="text-slate-500 dark:text-slate-400 text-center py-4">{{ __('No overdue bills') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
