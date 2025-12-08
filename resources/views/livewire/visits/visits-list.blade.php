@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold">{{ __('Visits') }}</h1>

    <div class="grid grid-cols-4 gap-4">
        <input type="text" wire:model.live="search" placeholder="{{ __('Search') }}" class="form-input">
        <select wire:model.live="doctorFilter" class="form-input">
            <option value="">{{ __('All Doctors') }}</option>
            @foreach($doctors as $doctor)
                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
            @endforeach
        </select>
        <input type="date" wire:model.live="dateFilter" class="form-input">
        <select wire:model.live="billStatusFilter" class="form-input">
            <option value="">{{ __('All Statuses') }}</option>
            <option value="billed">{{ __('Billed') }}</option>
            <option value="unbilled">{{ __('Unbilled') }}</option>
        </select>
    </div>

    <table class="w-full border-collapse bg-white dark:bg-gray-800 rounded">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-2 text-left">{{ __('Patient') }}</th>
                <th class="px-4 py-2 text-left">{{ __('File') }}</th>
                <th class="px-4 py-2 text-left">{{ __('Doctor') }}</th>
                <th class="px-4 py-2 text-left">{{ __('Date') }}</th>
                <th class="px-4 py-2 text-left">{{ __('Procedures') }}</th>
                <th class="px-4 py-2 text-left">{{ __('Status') }}</th>
                <th class="px-4 py-2 text-left">{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($visits as $visit)
                <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2">{{ $visit->patient->name }}</td>
                    <td class="px-4 py-2">{{ $visit->patient->file_number }}</td>
                    <td class="px-4 py-2">{{ $visit->doctor->name }}</td>
                    <td class="px-4 py-2">{{ $visit->visit_date->format('Y-m-d') }}</td>
                    <td class="px-4 py-2">{{ $visit->procedures->count() }}</td>
                    <td class="px-4 py-2">
                        @if($visit->bill)
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded">{{ __('Billed') }}</span>
                        @else
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded">{{ __('Unbilled') }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-2"><a href="{{ route('visits.show', $visit->id) }}" class="text-blue-600">{{ __('View') }}</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-2 text-center">{{ __('No visits found') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $visits->links() }}
</div>
@endsection
