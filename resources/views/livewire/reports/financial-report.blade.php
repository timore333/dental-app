<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('Financial Report') }}</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ __('Revenue, Payments & Insurance Analysis') }}</p>
        </div>

        <!-- Report Type Tabs -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
            <div class="flex gap-4 mb-6 border-b dark:border-slate-700">
                @foreach(['summary' => 'Summary', 'detailed' => 'Detailed', 'by-procedure' => 'By Procedure', 'by-insurance' => 'By Insurance'] as $type => $label)
                    <button
                        wire:click="updateReportType('{{ $type }}')"
                        class="pb-4 px-4 font-medium transition border-b-2 {{ $reportType === $type ? 'border-teal-500 text-teal-600 dark:text-teal-400' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('From Date') }}</label>
                    <input
                        type="date"
                        wire:model="fromDate"
                        class="w-full px-4 py-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('To Date') }}</label>
                    <input
                        type="date"
                        wire:model="toDate"
                        class="w-full px-4 py-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('Insurance Company') }}</label>
                    <select
                        wire:model="insuranceCompanyFilter"
                        class="w-full px-4 py-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white"
                    >
                        <option value="">{{ __('All Companies') }}</option>
                        @foreach($insuranceCompanies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button
                        wire:click="$refresh"
                        class="flex-1 px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg font-medium transition"
                    >
                        {{ __('Apply Filters') }}
                    </button>
                </div>
            </div>

            <!-- Export Buttons -->
            <div class="flex gap-3 mb-6">
                <button
                    wire:click="exportExcel"
                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition flex items-center gap-2"
                >
                    📊 {{ __('Export to Excel') }}
                </button>
                <button
                    wire:click="exportPDF"
                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition flex items-center gap-2"
                >
                    📄 {{ __('Export to PDF') }}
                </button>
                <button
                    onclick="window.print()"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition flex items-center gap-2"
                >
                    🖨️ {{ __('Print') }}
                </button>
            </div>

            <!-- Report Data Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b-2 dark:border-slate-600 bg-slate-100 dark:bg-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Date') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Description') }}</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Amount') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Type') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $entry)
                        <tr class="border-b dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $entry['date'] }}</td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $entry['description'] }}</td>
                            <td class="px-4 py-3 text-right font-bold text-green-600 dark:text-green-400">
                                ${{ number_format($entry['amount'], 2) }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($entry['type'] === 'cash') bg-blue-100 text-blue-700
                                    @elseif($entry['type'] === 'insurance') bg-purple-100 text-purple-700
                                    @else bg-gray-100 text-gray-700
                                    @endif
                                ">
                                    {{ ucfirst($entry['type']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($entry['status'] === 'completed') bg-green-100 text-green-700
                                    @elseif($entry['status'] === 'pending') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700
                                    @endif
                                ">
                                    {{ ucfirst($entry['status']) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                {{ __('No data available') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="border-t-2 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 font-bold">
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-right">{{ __('TOTAL:') }}</td>
                            <td class="px-4 py-3 text-right text-green-600 dark:text-green-400">
                                ${{ number_format($reportData->sum('amount'), 2) }}
                            </td>
                            <td colspan="2" class="px-4 py-3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $reportData->links() }}
            </div>
        </div>
    </div>
</div>
