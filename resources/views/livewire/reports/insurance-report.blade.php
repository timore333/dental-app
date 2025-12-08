<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-6">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-8">{{ __('Insurance Report') }}</h1>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
            <!-- Report Type Tabs -->
            <div class="flex gap-4 mb-6 border-b dark:border-slate-700 overflow-x-auto">
                @foreach(['summary' => 'Summary', 'requests' => 'Requests', 'claims' => 'Claims', 'performance' => 'Performance'] as $type => $label)
                    <button
                        wire:click="updateReportType('{{ $type }}')"
                        class="pb-4 px-4 font-medium transition border-b-2 whitespace-nowrap {{ $reportType === $type ? 'border-teal-500 text-teal-600 dark:text-teal-400' : 'border-transparent text-slate-600 dark:text-slate-400' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

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
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('Insurance Company') }}</label>
                    <select wire:model="insuranceCompanyId" class="w-full px-4 py-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        <option value="">{{ __('All Companies') }}</option>
                        @foreach($insuranceCompanies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
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
                <button wire:click="exportPDF" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium">
                    📄 {{ __('Export PDF') }}
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
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Insurance Company') }}</th>
                            @if($reportType === 'summary')
                                <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Total Requests') }}</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Total Amount') }}</th>
                            @elseif($reportType === 'requests')
                                <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Patient') }}</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Amount') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Status') }}</th>
                            @elseif($reportType === 'performance')
                                <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Total Requests') }}</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Approved') }}</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Approval Rate') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $item)
                        <tr class="border-b dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                            <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">
                                @if($reportType === 'requests' || $reportType === 'summary')
                                    {{ $item->insuranceCompany->name ?? $item->name }}
                                @else
                                    {{ $item->name }}
                                @endif
                            </td>
                            @if($reportType === 'summary')
                                <td class="px-4 py-3 text-right">{{ $item->total_requests }}</td>
                                <td class="px-4 py-3 text-right font-bold text-green-600 dark:text-green-400">
                                    ${{ number_format($item->total_amount, 2) }}
                                </td>
                            @elseif($reportType === 'requests')
                                <td class="px-4 py-3">{{ $item->appointment->patient->name }}</td>
                                <td class="px-4 py-3 text-right font-bold">
                                    ${{ number_format($item->estimated_cost, 2) }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($item->status === 'approved') bg-green-100 text-green-700
                                        @elseif($item->status === 'rejected') bg-red-100 text-red-700
                                        @else bg-yellow-100 text-yellow-700
                                        @endif
                                    ">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                            @elseif($reportType === 'performance')
                                <td class="px-4 py-3 text-right">{{ $item->total_requests }}</td>
                                <td class="px-4 py-3 text-right font-bold text-green-600 dark:text-green-400">
                                    {{ $item->approved_count }}
                                </td>
                                <td class="px-4 py-3 text-right font-bold">{{ $item->approval_rate }}%</td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                {{ __('No data available') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $reportData->links() }}</div>
        </div>
    </div>
</div>
