<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('Admin Dashboard') }}</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ __('Welcome back, System Administrator') }}</p>
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

        <!-- Metric Cards Row 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <x-metric-card
                label="{{ __('Total Patients') }}"
                :value="$metrics['total_patients']"
                icon="users"
                color="bg-blue-500"
            />
            <x-metric-card
                label="{{ __('Total Appointments') }}"
                :value="$metrics['total_appointments']"
                icon="calendar"
                color="bg-purple-500"
            />
            <x-metric-card
                label="{{ __('Revenue') }}"
                :value="'$' . number_format($metrics['total_revenue'], 2)"
                icon="trending-up"
                color="bg-green-500"
            />
            <x-metric-card
                label="{{ __('Pending Insurance') }}"
                :value="$metrics['pending_insurance']"
                icon="alert-circle"
                color="bg-orange-500"
            />
        </div>

        <!-- Metric Cards Row 2 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <x-metric-card
                label="{{ __('Active Doctors') }}"
                :value="$metrics['active_doctors']"
                icon="user-check"
                color="bg-indigo-500"
            />
            <x-metric-card
                label="{{ __('Completed Visits') }}"
                :value="$metrics['completed_appointments']"
                icon="check-circle"
                color="bg-emerald-500"
            />
            <x-metric-card
                label="{{ __('Pending Approvals') }}"
                :value="$metrics['pending_approvals']"
                icon="clipboard"
                color="bg-red-500"
            />
            <x-metric-card
                label="{{ __('Overdue Bills') }}"
                :value="$metrics['overdue_bills']"
                icon="alert"
                color="bg-yellow-500"
            />
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Appointment Status Chart -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Appointments by Status') }}</h2>
                <canvas id="appointmentChart"></canvas>
            </div>

            <!-- Revenue by Type Chart -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Revenue by Type') }}</h2>
                <canvas id="revenueChart"></canvas>
            </div>

            <!-- Procedures Chart -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Top Procedures') }}</h2>
                <canvas id="proceduresChart"></canvas>
            </div>

            <!-- Patient Growth Chart -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Patient Growth') }}</h2>
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Recent Appointments Table -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Recent Appointments') }}</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b dark:border-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Patient') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Doctor') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Date') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Appointment::whereBetween('appointment_date', [$fromDate, $toDate])->latest('appointment_date')->limit(5)->get() as $appointment)
                        <tr class="border-b dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $appointment->patient->name }}</td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $appointment->doctor->name }}</td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $appointment->appointment_date->format('M d, Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($appointment->status === 'completed') bg-green-100 text-green-700
                                    @elseif($appointment->status === 'scheduled') bg-blue-100 text-blue-700
                                    @elseif($appointment->status === 'cancelled') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700
                                    @endif
                                ">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.index') }}" class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow hover:shadow-lg transition text-center">
                <div class="text-2xl mb-2">👥</div>
                <div class="font-semibold text-slate-900 dark:text-white">{{ __('Manage Users') }}</div>
            </a>
            <a href="{{ route('reports.financial') }}" class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow hover:shadow-lg transition text-center">
                <div class="text-2xl mb-2">📊</div>
                <div class="font-semibold text-slate-900 dark:text-white">{{ __('Financial Reports') }}</div>
            </a>
            <a href="{{ route('settings.index') }}" class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow hover:shadow-lg transition text-center">
                <div class="text-2xl mb-2">⚙️</div>
                <div class="font-semibold text-slate-900 dark:text-white">{{ __('System Settings') }}</div>
            </a>
            <a href="{{ route('admin.audit-logs.index') }}" class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow hover:shadow-lg transition text-center">
                <div class="text-2xl mb-2">📝</div>
                <div class="font-semibold text-slate-900 dark:text-white">{{ __('Audit Logs') }}</div>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Appointment Status Chart
    new Chart(document.getElementById('appointmentChart'), {
        type: 'doughnut',
        data: {
            labels: @json($chartData['appointmentStatus']['labels']),
            datasets: [{
                data: @json($chartData['appointmentStatus']['data']),
                backgroundColor: ['#10b981', '#3b82f6', '#ef4444', '#f59e0b'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Revenue Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: @json($chartData['revenueByType']['labels']),
            datasets: [{
                label: 'Revenue',
                data: @json($chartData['revenueByType']['data']),
                backgroundColor: '#06b6d4',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Procedures Chart
    new Chart(document.getElementById('proceduresChart'), {
        type: 'bar',
        data: {
            labels: @json($chartData['procedures']['labels']),
            datasets: [{
                label: 'Count',
                data: @json($chartData['procedures']['data']),
                backgroundColor: '#8b5cf6',
                borderRadius: 8
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });

    // Patient Growth Chart
    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels: @json($chartData['patientGrowth']['labels']),
            datasets: [{
                label: 'New Patients',
                data: @json($chartData['patientGrowth']['data']),
                borderColor: '#14b8a6',
                backgroundColor: 'rgba(20, 184, 166, 0.1)',
                fill: true,
                tension: 0.4
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
