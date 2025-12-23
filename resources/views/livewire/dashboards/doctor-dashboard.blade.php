<div>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ __('Welcome back, ') . auth()->user()->name }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">
                    {{ __('Your performance overview') }}
                </p>
            </div>

            <!-- Date Range Filter -->
            <div class="flex gap-2">
                @foreach(['7days' => '7 Days', '30days' => '30 Days', '90days' => '90 Days', 'yearly' => 'Yearly'] as $range => $label)
                    <button
                        wire:click="setDateRange('{{ $range }}')"
                        @class([
                            'px-4 py-2 rounded-lg font-medium transition',
                            'bg-teal-500 text-white' => $dateRange === $range,
                            'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300' => $dateRange !== $range,
                        ])
                    >
                        {{ __($label) }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Key Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Appointments -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-teal-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                            {{ __('Total Appointments') }}
                        </p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ $metrics['total_appointments'] }}
                        </p>
                    </div>
                    <div class="text-4xl text-teal-500 opacity-20">📅</div>
                </div>
            </div>

            <!-- Completed Appointments -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                            {{ __('Completed') }}
                        </p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ $metrics['completed_appointments'] }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ round(($metrics['completed_appointments'] / max($metrics['total_appointments'], 1)) * 100) }}
                            %
                        </p>
                    </div>
                    <div class="text-4xl text-green-500 opacity-20">✓</div>
                </div>
            </div>

            <!-- Total Visits -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                            {{ __('Total Visits') }}
                        </p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ $metrics['total_visits'] }}
                        </p>
                    </div>
                    <div class="text-4xl text-blue-500 opacity-20">👤</div>
                </div>
            </div>

            <!-- Total Earnings -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                            {{ __('Total Earnings') }}
                        </p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ number_format($metrics['total_earnings'], 2) }} {{ __('EGP') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $metrics['total_payment_count'] }} {{ __('payments') }}
                        </p>
                    </div>
                    <div class="text-4xl text-green-600 opacity-20">💰</div>
                </div>
            </div>
        </div>

        <!-- Pending Section -->
        @if($metrics['pending_appointments'] > 0)
            <div
                class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="text-2xl">⚠️</div>
                    <div>
                        <p class="font-semibold text-orange-900 dark:text-orange-200">
                            {{ $metrics['pending_appointments'] }} {{ __('pending appointments') }}
                        </p>
                        <p class="text-sm text-orange-700 dark:text-orange-300">
                            {{ __('Review and confirm these appointments') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Appointment Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('Appointment Status') }}
                </h3>
                <canvas id="appointmentStatusChart" height="80"></canvas>
            </div>

            <!-- Earnings by Payment Method -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('Earnings by Payment Method') }}
                </h3>
                <canvas id="earningsByMethodChart" height="80"></canvas>
            </div>

            <!-- Top Procedures -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('Top Procedures Performed') }}
                </h3>
                <canvas id="proceduresChart" height="80"></canvas>
            </div>

            <!-- Appointments Over Time -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('Appointments Trend') }}
                </h3>
                <canvas id="appointmentsOverTimeChart" height="80"></canvas>
            </div>
        </div>
    </div>


        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const chartColors = {
                primary: '#208095',
                success: '#10b981',
                danger: '#ef4444',
                warning: '#f59e0b',
                info: '#3b82f6',
            };

            // Appointment Status Chart
            const appointmentCtx = document.getElementById('appointmentStatusChart').getContext('2d');
            new Chart(appointmentCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['appointmentStatus']['labels']),
                    datasets: [{
                        data: @json($chartData['appointmentStatus']['data']),
                        backgroundColor: [
                            chartColors.success,
                            chartColors.warning,
                            chartColors.danger,
                            chartColors.info,
                        ],
                        borderColor: '#fff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'}
                        }
                    }
                }
            });

            // Earnings by Method Chart
            const earningsCtx = document.getElementById('earningsByMethodChart').getContext('2d');
            new Chart(earningsCtx, {
                type: 'bar',
                data: {
                    labels: @json($chartData['earningsByMethod']['labels']),
                    datasets: [{
                        label: '{{ __("Amount (EGP)") }}',
                        data: @json($chartData['earningsByMethod']['data']),
                        backgroundColor: chartColors.success,
                        borderColor: chartColors.success,
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {beginAtZero: true}
                    },
                    plugins: {
                        legend: {
                            labels: {color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'}
                        }
                    }
                }
            });

            // Procedures Chart
            const proceduresCtx = document.getElementById('proceduresChart').getContext('2d');
            new Chart(proceduresCtx, {
                type: 'horizontalBar',
                data: {
                    labels: @json($chartData['procedures']['labels']),
                    datasets: [{
                        label: '{{ __("Count") }}',
                        data: @json($chartData['procedures']['data']),
                        backgroundColor: chartColors.info,
                        borderRadius: 4,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        x: {beginAtZero: true}
                    },
                    plugins: {
                        legend: {
                            labels: {color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'}
                        }
                    }
                }
            });

            // Appointments Over Time Chart
            const appointmentsTimeCtx = document.getElementById('appointmentsOverTimeChart').getContext('2d');
            new Chart(appointmentsTimeCtx, {
                type: 'line',
                data: {
                    labels: @json($chartData['appointmentsOverTime']['labels']),
                    datasets: [{
                        label: '{{ __("Appointments") }}',
                        data: @json($chartData['appointmentsOverTime']['data']),
                        borderColor: chartColors.primary,
                        backgroundColor: 'rgba(32, 128, 149, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: chartColors.primary,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {beginAtZero: true}
                    },
                    plugins: {
                        legend: {
                            labels: {color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'}
                        }
                    }
                }
            });
        </script>

</div>
