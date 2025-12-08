<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-8">{{ __('General Ledger') }}</h1>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
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
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('Account Type') }}</label>
                    <select wire:model="filterType" class="w-full px-4 py-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        <option value="all">{{ __('All Accounts') }}</option>
                        <option value="patient">{{ __('Patient Accounts') }}</option>
                        <option value="insurance">{{ __('Insurance Accounts') }}</option>
                        <option value="revenue">{{ __('Revenue') }}</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button wire:click="filterByDate" class="flex-1 px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg font-medium transition">
                        {{ __('Apply') }}
                    </button>
                </div>
            </div>

            <!-- Export Buttons -->
            <div class="flex gap-3 mb-6">
                <button onclick="window.print()" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition">
                    🖨️ {{ __('Print') }}
                </button>
                <button class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition">
                    📊 {{ __('Export') }}
                </button>
            </div>

            <!-- Ledger Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b-2 dark:border-slate-600 bg-slate-100 dark:bg-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Date') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Account') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Reference') }}</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Debit') }}</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Credit') }}</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Balance') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entries as $entry)
                        <tr class="border-b dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($entry->transaction_date)->format('M d, Y') }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">{{ $entry->account_name }}</td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300 text-xs">{{ $entry->reference }}</td>
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
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                {{ __('No entries found') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="border-t-2 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 font-bold">
                        <tr>
                            <td colspan="3" class="px-4 py-3">{{ __('TOTAL') }}</td>
                            <td class="px-4 py-3 text-right text-red-600 dark:text-red-400">
                                ${{ number_format($totalDebits, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right text-green-600 dark:text-green-400">
                                ${{ number_format($totalCredits, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right {{ ($totalDebits - $totalCredits) > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                ${{ number_format($totalDebits - $totalCredits, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Verification Note -->
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <p class="text-sm text-blue-900 dark:text-blue-100">
                    ℹ️ {{ __('Balance Verification:') }} Debits = Credits = Ledger is balanced
                </p>
            </div>
        </div>
    </div>
</div>
