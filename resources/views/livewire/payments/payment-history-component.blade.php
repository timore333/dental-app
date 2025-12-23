<div class="space-y-4">
    <!-- Filters -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium">{{ __('messages.payment_method') }}</label>
            <select wire:model.live="paymentMethod" class="form-control mt-1">
                <option value="">{{ __('messages.all') }}</option>
                <option value="cash">{{ __('messages.cash') }}</option>
                <option value="cheque">{{ __('messages.cheque') }}</option>
                <option value="card">{{ __('messages.card') }}</option>
                <option value="bank_transfer">{{ __('messages.bank_transfer') }}</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">{{ __('messages.from_date') }}</label>
            <input type="date" wire:model.live="fromDate" class="form-control mt-1">
        </div>

        <div>
            <label class="block text-sm font-medium">{{ __('messages.to_date') }}</label>
            <input type="date" wire:model.live="toDate" class="form-control mt-1">
        </div>

        <div>
            <label class="block text-sm font-medium">{{ __('messages.search') }}</label>
            <input type="text" wire:model.live="searchReference" class="form-control mt-1" placeholder="{{ __('messages.receipt_or_reference') }}">
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse border border-gray-200 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-left">
                        <button wire:click="sort('receipt_number')" class="font-semibold hover:text-teal-500">
                            {{ __('messages.receipt') }}
                            @if($sortBy === 'receipt_number')
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                            @endif
                        </button>
                    </th>
                    <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-left">
                        <button wire:click="sort('payment_date')" class="font-semibold hover:text-teal-500">
                            {{ __('messages.date') }}
                        </button>
                    </th>
                    <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-left">{{ __('messages.patient') }}</th>
                    <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-left">{{ __('messages.bill') }}</th>
                    <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-right">
                        <button wire:click="sort('amount')" class="font-semibold hover:text-teal-500">
                            {{ __('messages.amount') }}
                        </button>
                    </th>
                    <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-left">{{ __('messages.method') }}</th>
                    <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-center">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="border border-gray-200 dark:border-gray-700 px-4 py-2">{{ $payment->receipt_number }}</td>
                    <td class="border border-gray-200 dark:border-gray-700 px-4 py-2">{{ $payment->payment_date->format('Y-m-d') }}</td>
                    <td class="border border-gray-200 dark:border-gray-700 px-4 py-2">{{ $payment->patient->name }}</td>
                    <td class="border border-gray-200 dark:border-gray-700 px-4 py-2">{{ $payment->bill?->bill_number ?? '-' }}</td>
                    <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-right font-medium">{{ number_format($payment->amount, 2) }}</td>
                    <td class="border border-gray-200 dark:border-gray-700 px-4 py-2">{{ ucfirst($payment->payment_method) }}</td>
                    <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-center space-x-2">
                        <a href="{{ route('payments.print', $payment) }}" class="text-blue-500 hover:underline text-sm">
                            {{ __('messages.print') }}
                        </a>
                        <button wire:click="reversePayment({{ $payment->id }})" wire:confirm="{{ __('messages.confirm_reverse') }}" class="text-red-500 hover:underline text-sm">
                            {{ __('messages.reverse') }}
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="border border-gray-200 dark:border-gray-700 px-4 py-4 text-center text-gray-500">
                        {{ __('messages.no_payments') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $payments->links() }}
    </div>

    <!-- Export -->
    <div class="mt-4 flex gap-2">
        <button wire:click="exportExcel" class="btn btn--outline">
            {{ __('messages.export_excel') }}
        </button>
    </div>
</div>
