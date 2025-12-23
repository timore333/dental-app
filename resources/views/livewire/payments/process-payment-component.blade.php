<div class="space-y-6">
    <!-- Tabs -->
    <div class="flex border-b border-gray-200 dark:border-gray-700">
        <button wire:click="$set('currentTab', 'payment')"
                class="px-4 py-2 font-medium {{ $currentTab === 'payment' ? 'border-b-2 border-teal-500 text-teal-500' : 'text-gray-500' }}">
            {{ __('messages.payment') }}
        </button>
        @if($isAdvancePayment || ($bill && $bill->status !== 'draft'))
        <button wire:click="$set('currentTab', 'confirmation')"
                class="px-4 py-2 font-medium {{ $currentTab === 'confirmation' ? 'border-b-2 border-teal-500 text-teal-500' : 'text-gray-500' }}">
            {{ __('messages.confirmation') }}
        </button>
        @endif
    </div>

    @if($currentTab === 'payment')
    <div class="space-y-4">
        <!-- Payment Type Toggle -->
        <div class="flex items-center">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" wire:model.live="isAdvancePayment" class="rounded">
                <span>{{ __('messages.advance_payment') }}</span>
            </label>
        </div>

        <!-- Patient Selection -->
        @unless($patient)
        <div>
            <label class="block text-sm font-medium">{{ __('messages.patient') }}</label>
            <select wire:model.live="patientId" class="form-control mt-1">
                <option value="">{{ __('messages.select_patient') }}</option>
                @foreach(\App\Models\Patient::active()->get() as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
            @error('patient_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        @endunless

        <!-- Bill Selection -->
        @unless($isAdvancePayment)
        <div>
            <label class="block text-sm font-medium">{{ __('messages.bill') }}</label>
            <select wire:model.live="bill" class="form-control mt-1">
                <option value="">{{ __('messages.select_bill') }}</option>
                @if($patient)
                    @foreach($patient->bills()->unpaid()->get() as $b)
                    <option value="{{ $b->id }}">{{ $b->bill_number }} - {{ $b->total_amount }} ({{ __('messages.due') }}: {{ $b->getAmountDue() }})</option>
                    @endforeach
                @endif
            </select>
            @error('bill_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        @endunless

        <!-- Amount -->
        <div>
            <label class="block text-sm font-medium">{{ __('messages.amount') }} *</label>
            <input type="number" step="0.01" wire:model="amount" class="form-control mt-1" placeholder="0.00">
            @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            @if($suggestedAmounts && !$isAdvancePayment)
            <div class="mt-2 flex gap-2">
                @foreach($suggestedAmounts as $suggested)
                <button type="button" wire:click="updateAmount({{ $suggested }})" class="text-sm px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded">
                    {{ $suggested }}
                </button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Payment Method -->
        <div>
            <label class="block text-sm font-medium">{{ __('messages.payment_method') }} *</label>
            <select wire:model="paymentMethod" class="form-control mt-1">
                <option value="cash">{{ __('messages.cash') }}</option>
                <option value="cheque">{{ __('messages.cheque') }}</option>
                <option value="card">{{ __('messages.card') }}</option>
                <option value="bank_transfer">{{ __('messages.bank_transfer') }}</option>
                @if($patient?->isInsurance())
                <option value="insurance">{{ __('messages.insurance') }}</option>
                @endif
            </select>
            @error('paymentMethod') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Payment Date -->
        <div>
            <label class="block text-sm font-medium">{{ __('messages.payment_date') }} *</label>
            <input type="date" wire:model="paymentDate" class="form-control mt-1" max="{{ now()->format('Y-m-d') }}">
            @error('paymentDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Reference Number -->
        <div>
            <label class="block text-sm font-medium">{{ __('messages.reference_number') }}</label>
            <input type="text" wire:model="referenceNumber" class="form-control mt-1">
        </div>

        <!-- Advance Credits -->
        @if(!$isAdvancePayment && $availableCredits)
        <div class="p-3 bg-blue-50 dark:bg-blue-900 rounded">
            <label class="flex items-center gap-2">
                <input type="checkbox" wire:model="applyAdvanceCredit">
                <span class="text-sm font-medium">{{ __('messages.apply_advance_credit') }}</span>
            </label>
            @if($applyAdvanceCredit)
            <select wire:model="advanceCreditId" class="form-control mt-2">
                <option value="">{{ __('messages.select_credit') }}</option>
                @foreach($availableCredits as $credit)
                <option value="{{ $credit['id'] }}">
                    {{ __('messages.credit') }} #{{ $credit['id'] }} - {{ $credit['remaining_balance'] }} {{ __('messages.expires') }} {{ $credit['expires_at'] }}
                </option>
                @endforeach
            </select>
            @endif
        </div>
        @endif

        <!-- Notes -->
        <div>
            <label class="block text-sm font-medium">{{ __('messages.notes') }}</label>
            <textarea wire:model="notes" class="form-control mt-1" rows="3"></textarea>
        </div>

        <!-- Errors -->
        @if($errors->has('general'))
        <div class="p-3 bg-red-50 border border-red-200 rounded text-red-700">
            {{ $errors->first('general') }}
        </div>
        @endif

        <!-- Submit -->
        <button wire:click="processPayment" wire:loading.attr="disabled" class="w-full btn btn--primary">
            <span wire:loading.remove>{{ __('messages.process_payment') }}</span>
            <span wire:loading>
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        </button>
    </div>
    @endif

    @if($currentTab === 'confirmation')
    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded">
        <h3 class="font-bold mb-3">{{ __('messages.payment_summary') }}</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span>{{ __('messages.patient') }}:</span>
                <span class="font-medium">{{ $patient?->name }}</span>
            </div>
            @if($bill)
            <div class="flex justify-between">
                <span>{{ __('messages.bill') }}:</span>
                <span class="font-medium">{{ $bill->bill_number }}</span>
            </div>
            @endif
            <div class="flex justify-between">
                <span>{{ __('messages.amount') }}:</span>
                <span class="font-medium">{{ number_format($amount, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span>{{ __('messages.method') }}:</span>
                <span class="font-medium">{{ ucfirst($paymentMethod) }}</span>
            </div>
        </div>

        <div class="mt-4 flex gap-2">
            <button wire:click="$set('currentTab', 'payment')" class="flex-1 btn btn--outline">
                {{ __('messages.back') }}
            </button>
            <button class="flex-1 btn btn--primary">
                {{ __('messages.confirm_and_print') }}
            </button>
        </div>
    </div>
    @endif
</div>
