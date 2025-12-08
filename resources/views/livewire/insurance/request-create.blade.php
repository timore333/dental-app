<div class="container">
    <h1 class="text-2xl font-bold mb-6">{{ __('Insurance Request') }}</h1>
    <form wire:submit="submitRequest" class="space-y-6">
        <div class="card">
            <h2 class="text-lg font-bold">{{ __('Procedures') }}</h2>
            <table class="w-full mt-4">
                <thead class="bg-gray-50">
                    <tr><th>{{ __('Name') }}</th><th>{{ __('Price') }}</th></tr>
                </thead>
                <tbody>
                    @foreach($procedures as $proc)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $proc['name'] ?? $proc->name }}</td>
                            <td class="px-4 py-2">{{ $proc['price'] ?? $proc->price }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card">
            <label class="block font-medium mb-1">{{ __('Upload Document') }}</label>
            <input type="file" wire:model="requestDocument" accept=".pdf,.jpg,.jpeg,.png" class="form-input w-full">
        </div>

        <button type="submit" class="btn btn-primary w-full">{{ __('Next') }}</button>
    </form>
</div>
