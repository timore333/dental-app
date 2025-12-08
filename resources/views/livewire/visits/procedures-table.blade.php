<div class="overflow-x-auto">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-2 text-left">{{ __('Code') }}</th>
                <th class="px-4 py-2 text-left">{{ __('Name') }}</th>
                <th class="px-4 py-2 text-right">{{ __('Price') }}</th>
                <th class="px-4 py-2 text-right">{{ __('Total') }}</th>
                <th class="px-4 py-2 text-left">{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($procedures as $proc)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $proc->code }}</td>
                    <td class="px-4 py-2">{{ $proc->name }}</td>
                    <td class="px-4 py-2 text-right">{{ $proc->price }}</td>
                    <td class="px-4 py-2 text-right">{{ $proc->price }}</td>
                    <td class="px-4 py-2"><button class="text-red-600">Remove</button></td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-2 text-center">{{ __('No procedures') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
