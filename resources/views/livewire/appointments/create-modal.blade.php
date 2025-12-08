<div class="modal">
    <h2 class="modal-title">{{ __('Create Appointment') }}</h2>
    <form wire:submit="saveAppointment" class="space-y-4">
        <div>
            <label class="block font-medium mb-1">{{ __('Patient') }}</label>
            <input type="number" wire:model="patientId" class="form-input w-full">
            @error('patientId') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block font-medium mb-1">{{ __('Doctor') }}</label>
            <select wire:model="doctorId" class="form-input w-full">
                <option value="">{{ __('Select') }}</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-full">{{ __('Create') }}</button>
    </form>
</div>
