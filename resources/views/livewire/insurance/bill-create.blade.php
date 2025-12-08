<div class="container">
    <h1 class="text-2xl font-bold mb-6">{{ __('Create Bill') }}</h1>
    <form wire:submit="createBill" class="space-y-6">
        <div class="card">
            <h2 class="text-lg font-bold mb-4">{{ __('Approved Procedures') }}</h2>
            <p>{{ __('Total Approved Amount') }}: {{ $approvedAmount }}</p>
        </div>

        <button type="submit" class="btn btn-primary w-full">{{ __('Create Bill') }}</button>
    </form>
</div>
