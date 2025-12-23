<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('Welcome back, System Administrator') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                {{ __('Here\'s your clinic overview') }}
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
        <!-- Total Patients -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-teal-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                        {{ __('Total Patients') }}
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $metrics['total_patients'] }}
                    </p>
                </div>
                <div class="text-4xl text-teal-500 opacity-20">👥</div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                        {{ __('Total Revenue') }}
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($metrics['total_revenue'], 2) }} {{ __('EGP') }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ $metrics['total_payments_count'] }} {{ __('payments') }}
                    </p>
                </div>
                <div class="text-4xl text-green-500 opacity-20">💰</div>
            </div>
        </div>

        <!-- Completed Appointments -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                        {{ __('Completed Appointments') }}
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $metrics['completed_appointments'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('of') }} {{ $metrics['total_appointments'] }} {{ __('total') }}
                    </p>
                </div>
                <div class="text-4xl text-blue-500 opacity-20">✓</div>
            </div>
        </div>

        <!-- Pending Insurance -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                        {{ __('Pending Insurance') }}
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $metrics['pending_insurance'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('pending approvals') }}
                    </p>
                </div>
                <div class="text-4xl text-orange-500 opacity-20">🔄</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue by Payment Method -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Revenue by Payment Method') }}
            </h3>
            <canvas id="revenueByTypeChart" height="80"></canvas>
        </div>

        <!-- Appointment Status -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Appointment Status') }}
            </h3>
            <canvas id="appointmentStatusChart" height="80"></canvas>
        </div>

        <!-- Daily Revenue Trend -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Daily Revenue Trend') }}
            </h3>
            <canvas id="dailyRevenueChart" height="80"></canvas>
        </div>

        <!-- Top Procedures -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Top Procedures') }}
            </h3>
            <canvas id="proceduresChart" height="80"></canvas>
        </div>
    </div>

    <!-- Recent Payments Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ __('Recent Payments') }}
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Patient') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Amount') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Method') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Receipt') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Date') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentPayments as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $payment->bill->patient->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-green-600 dark:text-green-400">
                                {{ number_format($payment->amount, 2) }} {{ __('EGP') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span @class([
                                    'px-3 py-1 rounded-full text-xs font-medium',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $payment->payment_method === 'cash',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' => $payment->payment_method === 'card',
                                    'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' => $payment->payment_method === 'cheque',
                                    'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200' => $payment->payment_method === 'bank_transfer',
                                ])>
                                    {{ __(ucfirst($payment->payment_method)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $payment->receipt_number }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $payment->created_at->format('M d, Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                {{ __('No recent payments') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartColors = {
        primary: '#208095',
        success: '#10b981',
        danger: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6',
        light: '#f3f4f6',
    };

    // Revenue by Type Chart
    const revenueByTypeCtx = document.getElementById('revenueByTypeChart').getContext('2d');
    new Chart(revenueByTypeCtx, {
        type: 'doughnut',
        data: {
            labels: @json($chartData['revenueByType']['labels']),
            datasets: [{
                data: @json($chartData['revenueByType']['data']),
                backgroundColor: [
                    chartColors.success,
                    chartColors.info,
                    chartColors.warning,
                    chartColors.primary,
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
                    labels: { color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151' }
                }
            }
        }
    });

    // Appointment Status Chart
    const appointmentCtx = document.getElementById('appointmentStatusChart').getContext('2d');
    new Chart(appointmentCtx, {
        type: 'bar',
        data: {
            labels: @json($chartData['appointmentStatus']['labels']),
            datasets: [{
                label: '{{ __("Appointments") }}',
                data: @json($chartData['appointmentStatus']['data']),
                backgroundColor: chartColors.primary,
                borderColor: chartColors.primary,
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: {
                    labels: { color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151' }
                }
            }
        }
    });

    // Daily Revenue Chart
    const dailyRevenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    new Chart(dailyRevenueCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['dailyRevenue']['labels']),
            datasets: [{
                label: '{{ __("Daily Revenue") }}',
                data: @json($chartData['dailyRevenue']['data']),
                borderColor: chartColors.success,
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: chartColors.success,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: {
                    labels: { color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151' }
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
                x: { beginAtZero: true }
            },
            plugins: {
                legend: {
                    labels: { color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151' }
                }
            }
        }
    });
</script>
@endpush
