<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800">
    <!-- Header with Date Range Selection -->
    <div class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('Doctor Dashboard') }}</h1>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Welcome back, ' . auth()->user()->name) }}</p>
                </div>

                <!-- Date Range Selector -->
                <div class="flex flex-wrap gap-2">
                    <button
                        wire:click="setDateRange('7days')"
                        :class="{ 'bg-teal-500 text-white': dateRange === '7days', 'bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300': dateRange !== '7days' }"
                        class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 transition-colors">
                        {{ __('7 Days') }}
                    </button>
                    <button
                        wire:click="setDateRange('30days')"
                        :class="{ 'bg-teal-500 text-white': dateRange === '30days', 'bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300': dateRange !== '30days' }"
                        class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 transition-colors">
                        {{ __('30 Days') }}
                    </button>
                    <button
                        wire:click="setDateRange('90days')"
                        :class="{ 'bg-teal-500 text-white': dateRange === '90days', 'bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300': dateRange !== '90days' }"
                        class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 transition-colors">
                        {{ __('90 Days') }}
                    </button>
                    <button
                        wire:click="setDateRange('yearly')"
                        :class="{ 'bg-teal-500 text-white': dateRange === 'yearly', 'bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300': dateRange !== 'yearly' }"
                        class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 transition-colors">
                        {{ __('Yearly') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Key Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Total Appointments -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Total Appointments') }}</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ $metrics['total_appointments'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed Appointments -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Completed') }}</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ $metrics['completed_appointments'] }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Visits -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Total Visits') }}</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ $metrics['total_visits'] }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Earnings -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Total Earnings') }}</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($metrics['total_earnings'], 2) }} {{ __('EGP') }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Appointments -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Pending') }}</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ $metrics['pending_appointments'] }}</p>
                    </div>
                    <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Appointment Status Chart -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">{{ __('Appointment Status') }}</h2>
                <canvas id="appointmentStatusChart"></canvas>
            </div>

            <!-- Earnings by Payment Method -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">{{ __('Earnings by Method') }}</h2>
                <canvas id="earningsMethodChart"></canvas>
            </div>
        </div>

        <!-- Bottom Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Procedures -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">{{ __('Top Procedures') }}</h2>
                <canvas id="proceduresChart"></canvas>
            </div>

            <!-- Appointments Over Time -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">{{ __('Appointments Trend') }}</h2>
                <canvas id="appointmentsTrendChart"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Appointment Status Chart
        const appointmentStatusCtx = document.getElementById('appointmentStatusChart');
        if (appointmentStatusCtx) {
            new Chart(appointmentStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: @json(array_keys($chartData['appointmentStatus']['labels'] ?? [])),
                    datasets: [{
                        data: @json($chartData['appointmentStatus']['data'] ?? []),
                        backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        // Earnings by Method Chart
        const earningsCtx = document.getElementById('earningsMethodChart');
        if (earningsCtx) {
            new Chart(earningsCtx, {
                type: 'bar',
                data: {
                    labels: @json(array_keys($chartData['earningsByMethod']['labels'] ?? [])),
                    datasets: [{
                        label: '{{ __("Amount (EGP)") }}',
                        data: @json($chartData['earningsByMethod']['data'] ?? []),
                        backgroundColor: '#10B981',
                        borderColor: '#059669',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        // Procedures Chart
        const proceduresCtx = document.getElementById('proceduresChart');
        if (proceduresCtx) {
            new Chart(proceduresCtx, {
                type: 'horizontalBar',
                data: {
                    labels: @json(array_keys($chartData['procedures']['labels'] ?? [])),
                    datasets: [{
                        label: '{{ __("Count") }}',
                        data: @json($chartData['procedures']['data'] ?? []),
                        backgroundColor: '#8B5CF6'
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        x: { beginAtZero: true }
                    }
                }
            });
        }

        // Appointments Trend Chart
        const trendCtx = document.getElementById('appointmentsTrendChart');
        if (trendCtx) {
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: @json(array_keys($chartData['appointmentsOverTime']['labels'] ?? [])),
                    datasets: [{
                        label: '{{ __("Appointments") }}',
                        data: @json($chartData['appointmentsOverTime']['data'] ?? []),
                        borderColor: '#06B6D4',
                        backgroundColor: 'rgba(6, 182, 212, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    </script>
    @endpush
</div>
