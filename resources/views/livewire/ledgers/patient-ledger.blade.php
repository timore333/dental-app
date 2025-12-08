<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-6">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('Patient Ledger') }}</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $patient->name }} - {{ $patient->file_number }}</p>
            </div>
            <a href="{{ route('patients.show', $patient->id) }}" class="px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white rounded-lg font-medium transition">
                ← {{ __('Back') }}
            </a>
        </div>

        <!-- Patient Info Card -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Name') }}</p>
                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $patient->name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('File Number') }}</p>
                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $patient->file_number }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Phone') }}</p>
                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $patient->phone }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Current Balance') }}</p>
                    <p class="text-lg font-bold {{ $balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                        ${{ number_format($balance, 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Filters & Actions -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('From Date') }}</label>
                    <input type="date" wire:model="fromDate" class="w-full px-4 py-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('To Date') }}</label>
                    <input type="date" wire:model="toDate" class="w-full px-4 py-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white" />
                </div>
                <div class="flex items-end gap-2">
                    <button wire:click="filterByDate" class="flex-1 px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg font-medium transition">
                        {{ __('Apply') }}
                    </button>
                </div>
                <div class="flex items-end">
                    <button wire:click="printLedger" class="w-full px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition">
                        🖨️ {{ __('Print') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Ledger Table -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b-2 dark:border-slate-600 bg-slate-100 dark:bg-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Date') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Description') }}</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Debit') }}</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Credit') }}</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Balance') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entries as $entry)
                        <tr class="border-b dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($entry->transaction_date)->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $entry->description }}</td>
                            <td class="px-4 py-3 text-right {{ $entry->type === 'debit' ? 'font-bold text-red-600 dark:text-red-400' : '' }}">
                                {{ $entry->type === 'debit' ? '$' . number_format($entry->amount, 2) : '-' }}
                            </td>
                            <td class="px-4 py-3 text-right {{ $entry->type === 'credit' ? 'font-bold text-green-600 dark:text-green-400' : '' }}">
                                {{ $entry->type === 'credit' ? '$' . number_format($entry->amount, 2) : '-' }}
                            </td>
                            <td class="px-4 py-3 text-right font-bold {{ $entry->running_balance > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                ${{ number_format($entry->running_balance, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                {{ __('No transactions found') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="border-t-2 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 font-bold">
                        <tr>
                            <td colspan="2" class="px-4 py-3">{{ __('TOTAL') }}</td>
                            <td class="px-4 py-3 text-right text-red-600 dark:text-red-400">
                                ${{ number_format(collect($entries)->where('type', 'debit')->sum('amount'), 2) }}
                            </td>
                            <td class="px-4 py-3 text-right text-green-600 dark:text-green-400">
                                ${{ number_format(collect($entries)->where('type', 'credit')->sum('amount'), 2) }}
                            </td>
                            <td class="px-4 py-3 text-right {{ $balance > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                ${{ number_format($balance, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
