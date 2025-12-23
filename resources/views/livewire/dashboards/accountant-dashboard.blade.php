<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('Financial Metrics & Ledgers') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                {{ __('Complete financial overview') }}
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

    <!-- Key Financial Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Revenue -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                        {{ __('Total Revenue') }}
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($metrics['total_revenue'], 2) }} {{ __('EGP') }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ $metrics['total_payments_count'] }} {{ __('transactions') }}
                    </p>
                </div>
                <div class="text-4xl text-green-600 opacity-20">💵</div>
            </div>
        </div>

        <!-- Cash Payments -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                        {{ __('Cash Received') }}
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($metrics['cash_received'], 2) }} {{ __('EGP') }}
                    </p>
                </div>
                <div class="text-4xl text-green-500 opacity-20">💵</div>
            </div>
        </div>

        <!-- Card Payments -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                        {{ __('Card Payments') }}
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($metrics['card_received'], 2) }} {{ __('EGP') }}
                    </p>
                </div>
                <div class="text-4xl text-blue-500 opacity-20">💳</div>
            </div>
        </div>

        <!-- Outstanding Balance -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                        {{ __('Outstanding Balance') }}
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($metrics['outstanding_amount'], 2) }} {{ __('EGP') }}
                    </p>
                </div>
                <div class="text-4xl text-orange-500 opacity-20">⏳</div>
            </div>
        </div>
    </div>

    <!-- Additional Payment Methods -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Cheque Payments -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                        {{ __('Cheque Payments') }}
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($metrics['cheque_received'], 2) }} {{ __('EGP') }}
                    </p>
                </div>
                <div class="text-4xl text-purple-500 opacity-20">📄</div>
            </div>
        </div>

        <!-- Bank Transfer -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                        {{ __('Bank Transfer') }}
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($metrics['bank_transfer_received'], 2) }} {{ __('EGP') }}
                    </p>
                </div>
                <div class="text-4xl text-indigo-500 opacity-20">🏦</div>
            </div>
        </div>
    </div>

    <!-- Insurance Alert -->
    @if($metrics['pending_insurance'] > 0)
        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="text-2xl">⚠️</div>
                    <div>
                        <p class="font-semibold text-orange-900 dark:text-orange-200">
                            {{ $metrics['pending_insurance'] }} {{ __('pending insurance requests') }}
                        </p>
                        <p class="text-sm text-orange-700 dark:text-orange-300">
                            {{ number_format($metrics['pending_insurance_amount'], 2) }} {{ __('EGP') }} {{ __('pending approval') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue by Source -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Revenue by Payment Method') }}
            </h3>
            <canvas id="revenueBySourceChart" height="80"></canvas>
        </div>

        <!-- Daily Revenue Trend -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Daily Revenue Trend') }}
            </h3>
            <canvas id="dailyRevenueChart" height="80"></canvas>
        </div>

        <!-- Payment Methods Breakdown -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Payment Methods Breakdown') }}
            </h3>
            <canvas id="paymentMethodsChart" height="80"></canvas>
        </div>

        <!-- Outstanding by Patient -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Top Outstanding Balances') }}
            </h3>
            <canvas id="outstandingByPatientChart" height="80"></canvas>
        </div>
    </div>

    <!-- Outstanding Bills Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ __('Outstanding Bills') }}
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
                            {{ __('Bill ID') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Total Amount') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Paid') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Outstanding') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Due Date') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Status') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($outstandingBills as $bill)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $bill->patient->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                #{{ $bill->id }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ number_format($bill->total_amount, 2) }} {{ __('EGP') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-green-600 dark:text-green-400">
                                {{ number_format($bill->total_amount - $bill->balance, 2) }} {{ __('EGP') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-orange-600 dark:text-orange-400">
                                {{ number_format($bill->balance, 2) }} {{ __('EGP') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $bill->due_date?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span @class([
                                    'px-3 py-1 rounded-full text-xs font-medium',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $bill->status === 'paid',
                                    'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' => $bill->status === 'partial',
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => $bill->status === 'pending',
                                ])>
                                    {{ __(ucfirst($bill->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                {{ __('No outstanding bills') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
                            {{ __('Receipt #') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Date') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Reference') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentPayments as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
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
                                    {{ __(ucfirst(str_replace('_', ' ', $payment->payment_method))) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-mono">
                                {{ $payment->receipt_number }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $payment->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $payment->reference_number ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
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
        purple: '#a855f7',
        indigo: '#6366f1',
    };

    // Revenue by Source Chart
    const revenueSourceCtx = document.getElementById('revenueBySourceChart').getContext('2d');
    new Chart(revenueSourceCtx, {
        type: 'doughnut',
        data: {
            labels: @json($chartData['revenueBySource']['labels']),
            datasets: [{
                data: @json($chartData['revenueBySource']['data']),
                backgroundColor: [
                    chartColors.success,
                    chartColors.info,
                    chartColors.purple,
                    chartColors.indigo,
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

    // Payment Methods Chart
    const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
    new Chart(paymentMethodsCtx, {
        type: 'bar',
        data: {
            labels: @json($chartData['paymentMethods']['labels']),
            datasets: [{
                label: '{{ __("Amount (EGP)") }}',
                data: @json($chartData['paymentMethods']['data']),
                backgroundColor: [
                    chartColors.success,
                    chartColors.info,
                    chartColors.purple,
                    chartColors.indigo,
                ],
                borderRadius: 4,
                borderWidth: 0,
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

    // Outstanding by Patient Chart
    const outstandingCtx = document.getElementById('outstandingByPatientChart').getContext('2d');
    new Chart(outstandingCtx, {
        type: 'horizontalBar',
        data: {
            labels: @json($chartData['outstandingByPatient']['labels']),
            datasets: [{
                label: '{{ __("Outstanding (EGP)") }}',
                data: @json($chartData['outstandingByPatient']['data']),
                backgroundColor: chartColors.warning,
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
