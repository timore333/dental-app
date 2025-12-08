<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-6">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-8">{{ __('Patient Report') }}</h1>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
            <!-- Report Type Tabs -->
            <div class="flex gap-4 mb-6 border-b dark:border-slate-700">
                @foreach(['demographics' => 'Demographics', 'activity' => 'Activity', 'financial' => 'Financial'] as $type => $label)
                    <button
                        wire:click="updateReportType('{{ $type }}')"
                        class="pb-4 px-4 font-medium transition border-b-2 {{ $reportType === $type ? 'border-teal-500 text-teal-600 dark:text-teal-400' : 'border-transparent text-slate-600 dark:text-slate-400' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('From Date') }}</label>
                    <input type="date" wire:model="fromDate" class="w-full px-4 py-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('To Date') }}</label>
                    <input type="date" wire:model="toDate" class="w-full px-4 py-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white" />
                </div>
                <div class="flex items-end">
                    <button wire:click="$refresh" class="w-full px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg font-medium transition">
                        {{ __('Apply') }}
                    </button>
                </div>
            </div>

            <!-- Export Buttons -->
            <div class="flex gap-3 mb-6">
                <button wire:click="exportExcel" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium">
                    📊 {{ __('Export Excel') }}
                </button>
                <button onclick="window.print()" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium">
                    🖨️ {{ __('Print') }}
                </button>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b-2 dark:border-slate-600 bg-slate-100 dark:bg-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Name') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">
                                @if($reportType === 'demographics') {{ __('Age / Gender') }} @endif
                                @if($reportType === 'activity') {{ __('Appointments') }} @endif
                                @if($reportType === 'financial') {{ __('Total Spent') }} @endif
                            </th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">
                                @if($reportType === 'activity') {{ __('Visits') }} @endif
                            </th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Contact') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $patient)
                        <tr class="border-b dark:border-slate-700">
                            <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">{{ $patient->name }}</td>
                            <td class="px-4 py-3">
                                @if($reportType === 'demographics')
                                    {{ $patient->age }} yrs / {{ ucfirst($patient->gender) }}
                                @elseif($reportType === 'activity')
                                    {{ $patient->appointment_count ?? 0 }}
                                @elseif($reportType === 'financial')
                                    ${{ number_format($patient->total_spent ?? 0, 2) }}
                                @endif
                            </td>
                            @if($reportType === 'activity')
                            <td class="px-4 py-3">{{ $patient->visit_count ?? 0 }}</td>
                            @endif
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300 text-xs">{{ $patient->phone }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-500">{{ __('No data') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $reportData->links() }}</div>
        </div>
    </div>
</div>
