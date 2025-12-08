<div class="w-full">
    <div class="mb-4 flex gap-2">
        <input
            type="date"
            wire:model="fromDate"
            class="px-3 py-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm"
        />
        <input
            type="date"
            wire:model="toDate"
            class="px-3 py-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm"
        />
        <select wire:model="chartType" class="px-3 py-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm">
            <option value="line">Line Chart</option>
            <option value="bar">Bar Chart</option>
        </select>
        <button wire:click="$refresh" class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg text-sm font-medium">
            Update
        </button>
    </div>

    <canvas id="revenueChart"></canvas>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: @json($chartType),
        data: @json($chartData),
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
