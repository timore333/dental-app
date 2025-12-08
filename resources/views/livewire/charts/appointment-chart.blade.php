<div class="w-full">
    <div class="mb-4">
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
        <button wire:click="$refresh" class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg text-sm font-medium">
            Update
        </button>
    </div>

    <canvas id="appointmentChart"></canvas>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('appointmentChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: @json($chartData),
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endpush
