<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('Welcome back, Receptionist') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                {{ __('Quick overview of your clinic today') }}
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total Patients -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-teal-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-xs font-medium">
                        {{ __('Total Patients') }}
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $metrics['total_patients'] }}
                    </p>
                </div>
                <div class="text-3xl text-teal-500 opacity-20">👥</div>
            </div>
        </div>

        <!-- Appointments Today -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-xs font-medium">
                        {{ __('Today') }}
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $metrics['appointments_today'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('appointments') }}
                    </p>
                </div>
                <div class="text-3xl text-blue-500 opacity-20">📅</div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-xs font-medium">
                        {{ __('This Month') }}
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($metrics['revenue_month'], 0) }} {{ __('EGP') }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('revenue') }}
                    </p>
                </div>
                <div class="text-3xl text-green-500 opacity-20">💰</div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-xs font-medium">
                        {{ __('Pending') }}
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $metrics['pending_payments'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('bills') }}
                    </p>
                </div>
                <div class="text-3xl text-orange-500 opacity-20">⏳</div>
            </div>
        </div>

        <!-- Today Payments -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-xs font-medium">
                        {{ __('Today Payments') }}
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $metrics['total_payments_today'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('transactions') }}
                    </p>
                </div>
                <div class="text-3xl text-purple-500 opacity-20">📊</div>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    @if($metrics['pending_appointments'] > 0)
        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
            <div class="flex items-center gap-3">
                <div class="text-2xl">⚠️</div>
                <div>
                    <p class="font-semibold text-orange-900 dark:text-orange-200">
                        {{ $metrics['pending_appointments'] }} {{ __('pending appointments') }}
                    </p>
                    <p class="text-sm text-orange-700 dark:text-orange-300">
                        {{ __('Need confirmation') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Appointments -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __("Today's Appointments") }}
                </h3>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
                @forelse($todayAppointments as $appointment)
                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $appointment->start->format('H:i') }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $appointment->patient->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                    👨‍⚕️ {{ $appointment->doctor->name }}
                                </p>
                            </div>
                            <span @class([
                                'px-2 py-1 rounded text-xs font-medium',
                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $appointment->status === 'completed',
                                'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' => $appointment->status === 'scheduled',
                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' => $appointment->status === 'pending',
                            ])>
                                {{ __(ucfirst($appointment->status)) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        {{ __('No appointments for today') }}
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('Recent Payments') }}
                </h3>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
                @forelse($recentPayments as $payment)
                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $payment->bill->patient->name ?? 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $payment->created_at->format('M d, Y H:i') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $payment->receipt_number }}
                                </p>
                            </div>
                            <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                +{{ number_format($payment->amount, 0) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        {{ __('No recent payments') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Appointment Status -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Appointment Status') }}
            </h3>
            <canvas id="appointmentStatusChart" height="80"></canvas>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Payment Methods') }}
            </h3>
            <canvas id="paymentMethodsChart" height="80"></canvas>
        </div>

        <!-- Patient Growth -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('New Patients Growth') }}
            </h3>
            <canvas id="patientGrowthChart" height="80"></canvas>
        </div>

        <!-- Insurance Status -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Insurance Requests') }}
            </h3>
            <canvas id="insuranceStatusChart" height="80"></canvas>
        </div>
    </div>

    <!-- Pending Insurance Requests Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ __('Pending Insurance Requests') }}
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
                            {{ __('Insurance') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Amount') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Date') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($pendingInsuranceRequests as $request)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $request->appointment->patient->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $request->insuranceCompany->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ number_format($request->estimated_cost, 2) }} {{ __('EGP') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                    {{ __(ucfirst($request->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $request->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                {{ __('No pending insurance requests') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Overdue Bills Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ __('Overdue Bills') }}
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
                            {{ __('Amount Due') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Due Date') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                            {{ __('Days Overdue') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($overdueBills as $bill)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $bill->patient->name }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-red-600 dark:text-red-400">
                                {{ number_format($bill->balance, 2) }} {{ __('EGP') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $bill->due_date?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-orange-600 dark:text-orange-400">
                                {{ $bill->due_date ? now()->diffInDays($bill->due_date) : '—' }} {{ __('days') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                {{ __('No overdue bills') }}
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
                    labels: { color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151' }
                }
            }
        }
    });

    // Payment Methods Chart
    const paymentCtx = document.getElementById('paymentMethodsChart').getContext('2d');
    new Chart(paymentCtx, {
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

    // Patient Growth Chart
    const patientCtx = document.getElementById('patientGrowthChart').getContext('2d');
    new Chart(patientCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['patientGrowth']['labels']),
            datasets: [{
                label: '{{ __("New Patients") }}',
                data: @json($chartData['patientGrowth']['data']),
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
                y: { beginAtZero: true }
            },
            plugins: {
                legend: {
                    labels: { color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151' }
                }
            }
        }
    });

    // Insurance Status Chart
    const insuranceCtx = document.getElementById('insuranceStatusChart').getContext('2d');
    new Chart(insuranceCtx, {
        type: 'doughnut',
        data: {
            labels: @json($chartData['insuranceStatus']['labels']),
            datasets: [{
                data: @json($chartData['insuranceStatus']['data']),
                backgroundColor: [
                    chartColors.success,
                    chartColors.warning,
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
                    labels: { color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151' }
                }
            }
        }
    });
</script>
@endpush
