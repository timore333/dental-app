<div class="space-y-4">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold">{{ __('messages.advance_credits') }}</h3>
        <div class="flex items-center gap-2">
            <label class="text-sm">{{ __('messages.auto_apply') }}</label>
            <input type="checkbox" wire:model.live="autoApply" class="rounded">
        </div>
    </div>

    <!-- Credits List -->
    <div class="space-y-3">
        @forelse($credits as $credit)
        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <div class="text-sm font-medium">{{ __('messages.credit') }} #{{ $credit['id'] }}</div>
                    <div class="text-xs text-gray-500">{{ $credit['source_type'] }}</div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-green-600">{{ number_format($credit['remaining_balance'], 2) }}</div>
                    <div class="text-xs text-gray-500">
                        @if($credit['expires_at'])
                            {{ __('messages.expires') }}: {{ \Carbon\Carbon::parse($credit['expires_at'])->format('Y-m-d') }}
                        @else
                            {{ __('messages.never_expires') }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($credit['remaining_balance'] / $credit['amount']) * 100 }}%"></div>
            </div>

            <div class="flex gap-2 mt-3">
                <button wire:click="applyToNextBill" class="text-sm btn btn--primary">
                    {{ __('messages.apply_to_next_bill') }}
                </button>
                <button wire:click="toggleRefundForm({{ $credit['id'] }})" class="text-sm btn btn--outline">
                    {{ __('messages.refund') }}
                </button>
            </div>

            @if($showRefundForm && $selectedCreditId == $credit['id'])
            <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-800 rounded">
                <input type="number" wire:model="refundAmount" step="0.01" placeholder="{{ __('messages.refund_amount') }}" class="form-control mb-2">
                <button wire:click="processRefund" class="w-full btn btn--primary btn--sm">
                    {{ __('messages.process_refund') }}
                </button>
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-6 text-gray-500">
            {{ __('messages.no_advance_credits') }}
        </div>
        @endforelse
    </div>

    <!-- Summary -->
    <div class="p-4 bg-blue-50 dark:bg-blue-900 rounded">
        <div class="flex justify-between">
            <span>{{ __('messages.total_balance') }}:</span>
            <span class="font-bold">{{ number_format(collect($credits)->sum('remaining_balance'), 2) }}</span>
        </div>
    </div>
</div>
