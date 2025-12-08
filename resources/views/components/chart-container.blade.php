@props(['title' => 'Chart', 'chartComponent' => null])

<div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $title }}</h2>
        {{ $headerSlot ?? '' }}
    </div>

    <div class="chart-wrapper">
        {{ $slot }}
    </div>

    @if(isset($filter) && $filter)
        <div class="mt-6 pt-6 border-t dark:border-slate-700">
            {{ $filter }}
        </div>
    @endif
</div>
