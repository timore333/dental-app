<div class="container">
    <h1 class="text-2xl font-bold mb-6">{{ __('Record Approval') }}</h1>
    <form wire:submit="recordApproval" class="space-y-6">
        <div class="card">
            <label class="block font-medium mb-1">{{ __('Upload Approval') }}</label>
            <input type="file" wire:model="approvalDocument" accept=".pdf,.jpg,.jpeg,.png" class="form-input w-full">
        </div>

        <div class="card">
            <h2 class="text-lg font-bold mb-4">{{ __('Procedures') }}</h2>
            <div class="space-y-2">
                @foreach($procedures as $proc)
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="approvedProcedures" value="{{ $proc['id'] }}"> {{ $proc['name'] }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="card">
            <label class="block font-medium mb-1">{{ __('Approved Amount') }}</label>
            <input type="number" step="0.01" wire:model="approvedAmount" class="form-input w-full">
        </div>

        <button type="submit" class="btn btn-primary w-full">{{ __('Next') }}</button>
    </form>
</div>
