<div class="container mx-auto max-w-2xl">
    <h1 class="text-2xl font-bold mb-6">{{ __('Record Visit') }}</h1>

    <form wire:submit="submitVisit" class="space-y-6">
        <!-- Patient Info -->
        <div class="card">
            <h2 class="text-lg font-bold mb-4">{{ __('Patient Info') }}</h2>
            <div class="grid grid-cols-3 gap-4">
                <div><span class="label">{{ __('Name') }}</span><p>{{ $patient->getName() ?? '' }}</p></div>
                <div><span class="label">{{ __('File number') }}</span><p>{{ $patient->file_number ?? $patient->id}}</p></div>
                <div><span class="label">{{ __('Age') }}</span><p>{{ age($patient->date_of_birth) ?? '' }}</p></div>
            </div>
        </div>

        <!-- Visit Info -->
        <div class="card">
            <h2 class="text-lg font-bold mb-4">{{ __('Visit Info') }}</h2>
            <div class="space-y-4">
                <div>
                    <label class="block font-medium mb-1">{{ __('Date') }}</label>
                    <input type="date" wire:model="visitDate" class="form-input w-full">
                </div>
                <div>
                    <label class="block font-medium mb-1">{{ __('Chief Complaint') }}</label>
                    <textarea wire:model="chiefComplaint" rows="3" class="form-input w-full"></textarea>
                </div>
                <div>
                    <label class="block font-medium mb-1">{{ __('Diagnosis') }}</label>
                    <textarea wire:model="diagnosis" rows="3" class="form-input w-full"></textarea>
                </div>
            </div>
        </div>

        <!-- Procedures -->
        <div class="card">
            <h2 class="text-lg font-bold mb-4">{{ __('Procedures') }}</h2>
            <div class="space-y-4">
                <div class="flex gap-2">
                    <select wire:model="selectedProcedure"  class="form-input flex-1">
                        <option value="">{{ __('Select Procedure') }}</option>
                        @foreach($procedures as $proc)
                            <option value="{{ $proc->id }}">{{ $proc->name }} - {{ $proc->price }}</option>
                        @endforeach
                    </select>
                    <button type="button" wire:click="addProcedure" class="btn btn-primary">{{ __('Add') }}</button>
                </div>

                @if(!empty($selectedProcedures))
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">{{ __('Name') }}</th>
                                <th class="text-left py-2">{{ __('Price') }}</th>
                                <th class="text-left py-2">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selectedProcedures as $index => $proc)
                                <tr class="border-b">
                                    <td class="py-2">{{ $proc['name'] }}</td>
                                    <td class="py-2">{{ $proc['price'] }}</td>
                                    <td class="py-2"><button type="button" wire:click="removeProcedure({{ $index }})" class="text-red-600">{{ __('Remove') }}</button></td>
                                </tr>
                            @endforeach
                            <tr class="font-bold">
                                <td class="py-2">{{ __('Total') }}</td>
                                <td class="py-2">{{ $this->calculateTotal() }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-full">{{ __('Save Visit') }}</button>
    </form>
</div>
