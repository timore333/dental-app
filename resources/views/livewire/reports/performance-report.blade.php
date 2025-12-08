<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-6">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-8">{{ __('Performance Report') }}</h1>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('From Date') }}</label>
                    <input type="date" wire:model="fromDate" class="w-full px-4 py-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('To Date') }}</label>
                    <input type="date" wire:model="toDate" class="w-full px-4 py-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('Doctor') }}</label>
                    <select wire:model="doctorId" class="w-full px-4 py-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        <option value="">{{ __('All Doctors') }}</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button wire:click="$refresh" class="w-full px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg font-medium transition">
                        {{ __('Apply') }}
                    </button>
                </div>
            </div>

            <!-- Export Button -->
            <div class="flex gap-3 mb-6">
                <button wire:click="exportPDF" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium">
                    📄 {{ __('Export PDF') }}
                </button>
                <button onclick="window.print()" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium">
                    🖨️ {{ __('Print') }}
                </button>
            </div>

            <!-- Performance Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b-2 dark:border-slate-600 bg-slate-100 dark:bg-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Doctor') }}</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-700 dark:text-slate-300">{{ __('Appointments') }}</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-700 dark:text-slate-300">{{ __('Completed') }}</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-700 dark:text-slate-300">{{ __('No-Show Rate') }}</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-700 dark:text-slate-300">{{ __('Visits') }}</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Revenue') }}</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Per Appt') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($metricsData as $metric)
                        <tr class="border-b dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                            <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">{{ $metric['doctor_name'] }}</td>
                            <td class="px-4 py-3 text-center">{{ $metric['total_appointments'] }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                    {{ $metric['completed_appointments'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 {{ $metric['no_show_rate'] > 10 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} rounded text-xs font-semibold">
                                    {{ $metric['no_show_rate'] }}%
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">{{ $metric['visit_count'] }}</td>
                            <td class="px-4 py-3 text-right font-bold text-green-600 dark:text-green-400">
                                ${{ number_format($metric['total_revenue'], 2) }}
                            </td>
                            <td class="px-4 py-3 text-right font-bold">
                                ${{ number_format($metric['revenue_per_appointment'], 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                {{ __('No data available') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
