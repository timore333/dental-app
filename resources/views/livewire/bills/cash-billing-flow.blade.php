<div class="container mx-auto max-w-2xl">
    <div class="tabs mb-6">
        <button wire:click="$set('tab', 'bill')" class="tab-btn {{ $tab === 'bill' ? 'active' : '' }}">{{ __('Bill') }}</button>
        <button wire:click="$set('tab', 'payment')" class="tab-btn {{ $tab === 'payment' ? 'active' : '' }}">{{ __('Payment') }}</button>
        @if($bill->payment_status === 'fully_paid')
            <button wire:click="$set('tab', 'receipt')" class="tab-btn {{ $tab === 'receipt' ? 'active' : '' }}">{{ __('Receipt') }}</button>
        @endif
    </div>

    @if($tab === 'bill')
        <div class="card">
            <h2 class="text-2xl font-bold mb-4">{{ __('Bill Details') }}</h2>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div><span class="label">{{ __('Bill #') }}</span><p>{{ $bill->bill_number }}</p></div>
                <div><span class="label">{{ __('Date') }}</span><p>{{ $bill->created_at->format('Y-m-d') }}</p></div>
            </div>

            <table class="w-full mb-4">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">{{ __('Procedure') }}</th>
                        <th class="px-4 py-2 text-right">{{ __('Price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bill->billItems as $item)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $item->procedure->name }}</td>
                            <td class="px-4 py-2 text-right">{{ $item->price }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="space-y-2 text-right">
                <p><strong>{{ __('Total') }}:</strong> {{ $bill->total_amount }}</p>
                <p><strong>{{ __('Paid') }}:</strong> {{ $bill->total_paid }}</p>
                <p><strong>{{ __('Due') }}:</strong> {{ $bill->amount_due }}</p>
            </div>
        </div>
    @elseif($tab === 'payment')
        <form wire:submit="recordPayment" class="card space-y-4">
            <h2 class="text-2xl font-bold mb-4">{{ __('Record Payment') }}</h2>

            <div>
                <label class="block font-medium mb-1">{{ __('Amount') }}</label>
                <input type="number" step="0.01" wire:model="paymentAmount" class="form-input w-full">
                @error('paymentAmount') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-medium mb-1">{{ __('Method') }}</label>
                <select wire:model="paymentMethod" class="form-input w-full">
                    <option value="cash">{{ __('Cash') }}</option>
                    <option value="cheque">{{ __('Cheque') }}</option>
                    <option value="card">{{ __('Card') }}</option>
                    <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">{{ __('Date') }}</label>
                <input type="date" wire:model="paymentDate" class="form-input w-full">
            </div>

            @if(in_array($paymentMethod, ['cheque', 'card']))
                <div>
                    <label class="block font-medium mb-1">{{ __('Reference') }}</label>
                    <input type="text" wire:model="referenceNumber" class="form-input w-full">
                </div>
            @endif

            <button type="submit" class="btn btn-primary w-full">{{ __('Record Payment') }}</button>
        </form>
    @else
        <div class="card">
            <h2 class="text-2xl font-bold mb-4">{{ __('Receipt') }}</h2>
            <a href="{{ route('receipts.print', ['receipt' => 'id']) }}" class="btn btn-primary">{{ __('Print') }}</a>
        </div>
    @endif
</div>
