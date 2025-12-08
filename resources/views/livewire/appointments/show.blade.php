<div class="container">
    <a href="{{ route('appointments.index') }}" class="btn btn-secondary mb-4">{{ __('Back') }}</a>
    <div class="card">
        <h1 class="text-2xl font-bold">{{ __('Appointment Details') }}</h1>
        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <span class="label">{{ __('Patient') }}</span>
                <p>{{ $appointment->patient->name }}</p>
            </div>
            <div>
                <span class="label">{{ __('Doctor') }}</span>
                <p>{{ $appointment->doctor?->name ?? 'N/A' }}</p>
            </div>
            <div>
                <span class="label">{{ __('Date') }}</span>
                <p>{{ $appointment->appointment_date->format('Y-m-d H:i') }}</p>
            </div>
            <div>
                <span class="label">{{ __('Status') }}</span>
                <p>{{ ucfirst($appointment->status) }}</p>
            </div>
        </div>
    </div>
</div>
