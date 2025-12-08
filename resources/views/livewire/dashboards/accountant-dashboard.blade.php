<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('Accountant Dashboard') }}</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ __('Financial Metrics & Ledgers') }}</p>
            </div>
            <div class="flex gap-3">
                @foreach(['7days' => '7 Days', '30days' => '30 Days', '90days' => '90 Days', 'yearly' => 'Yearly'] as $range => $label)
                    <button
                        wire:click="setDateRange('{{ $range }}')"
                        class="px-4 py-2 rounded-lg font-medium transition {{ $dateRange === $range ? 'bg-teal-500 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Primary Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <x-metric-card
                label="{{ __('Total Revenue') }}"
                :value="'$' . number_format($metrics['total_revenue'], 2)"
                icon="trending-up"
                color="bg-green-500"
            />
            <x-metric-card
                label="{{ __('Cash Received') }}"
                :value="'$' . number_format($metrics['cash_received'], 2)"
                icon="credit-card"
                color="bg-blue-500"
            />
            <x-metric-card
                label="{{ __('Insurance Amount') }}"
                :value="'$' . number_format($metrics['insurance_amount'], 2)"
                icon="shield"
                color="bg-purple-500"
            />
            <x-metric-card
                label="{{ __('Outstanding') }}"
                :value="'$' . number_format($metrics['outstanding_amount'], 2)"
                icon="alert-circle"
                color="bg-orange-500"
            />
        </div>

        <!-- Secondary Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <x-metric-card
                label="{{ __('Pending Insurance Cases') }}"
                :value="$metrics['pending_insurance']"
                icon="clipboard"
                color="bg-red-500"
            />
            <x-metric-card
                label="{{ __('Pending Insurance Amount') }}"
                :value="'$' . number_format($metrics['pending_insurance_amount'], 2)"
                icon="dollar-sign"
                color="bg-yellow-500"
            />
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Revenue by Source -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Revenue by Source') }}</h2>
                <canvas id="sourceChart"></canvas>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Payment Methods') }}</h2>
                <canvas id="methodsChart"></canvas>
            </div>

            <!-- Monthly Revenue Trend -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 lg:col-span-2">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Monthly Revenue Trend') }}</h2>
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Outstanding Bills -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Top 10 Outstanding Bills') }}</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b dark:border-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Patient') }}</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">{{ __('Balance') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Last Updated') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($outstandingBills as $bill)
                        <tr class="border-b dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                            <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">{{ $bill->name }}</td>
                            <td class="px-4 py-3 text-right font-bold text-red-600 dark:text-red-400">
                                ${{ number_format($bill->balance, 2) }}
                            </td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300 text-xs">
                                {{ \Carbon\Carbon::parse($bill->updated_at)->format('M d, Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                {{ __('No outstanding bills') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Recent Payments') }}</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b dark:border-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Patient') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Amount') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Method') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $payment)
                        <tr class="border-b dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                            <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">
                                {{ $payment->appointment?->patient->name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 font-bold text-green-600 dark:text-green-400">
                                ${{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($payment->payment_method === 'cash') bg-blue-100 text-blue-700
                                    @elseif($payment->payment_method === 'insurance') bg-purple-100 text-purple-700
                                    @else bg-green-100 text-green-700
                                    @endif
                                ">
                                    {{ ucfirst($payment->payment_method) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300 text-xs">
                                {{ $payment->created_at->format('M d, Y H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                {{ __('No recent payments') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue by Source
    new Chart(document.getElementById('sourceChart'), {
        type: 'doughnut',
        data: {
            labels: @json($chartData['revenueBySource']['labels']),
            datasets: [{
                data: @json($chartData['revenueBySource']['data']),
                backgroundColor: ['#06b6d4', '#3b82f6', '#8b5cf6'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Payment Methods
    new Chart(document.getElementById('methodsChart'), {
        type: 'bar',
        data: {
            labels: @json($chartData['paymentMethods']['labels']),
            datasets: [{
                label: 'Amount',
                data: @json($chartData['paymentMethods']['data']),
                backgroundColor: '#10b981',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Monthly Trend
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: @json($chartData['monthlyRevenue']['labels']),
            datasets: [{
                label: 'Revenue',
                data: @json($chartData['monthlyRevenue']['data']),
                borderColor: '#14b8a6',
                backgroundColor: 'rgba(20, 184, 166, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endpush
