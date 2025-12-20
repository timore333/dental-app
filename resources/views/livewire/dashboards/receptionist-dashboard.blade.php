<div>

    <!-- resources/views/livewire/dashboards/receptionist-dashboard.blade.php -->
    <div
        class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <div class="mb-4">
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ __('Receptionist Dashboard') }}</h1>
            </div>
            <div class="sm:flex items-center justify-between gap-4">
                <form class="flex gap-2" wire:submit="setDateRange">
                    <select wire:model="dateRange" wire:change="setDateRange"
                            class="px-3 py-2 border border-gray-300 rounded-lg bg-white dark:bg-gray-700 dark:border-gray-600 text-sm">
                        <option value="7days">{{ __('Last 7 Days') }}</option>
                        <option value="30days" selected>{{ __('Last 30 Days') }}</option>
                        <option value="90days">{{ __('Last 90 Days') }}</option>
                        <option value="yearly">{{ __('Yearly') }}</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Section -->
    <div
        class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full">
            <div class="grid w-full grid-cols-1 gap-4 mb-4 xl:grid-cols-5 2xl:col-span-2 dark:text-white">
                <!-- Total Appointments Card -->
                <div
                    class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium dark:text-gray-400">{{ __('Total Appointments') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $metrics['total_appointments'] ?? 0 }}</p>
                        </div>
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/>
                        </svg>
                    </div>
                </div>

                <!-- Scheduled Appointments Card -->
                <div
                    class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium dark:text-gray-400">{{ __('Scheduled') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $metrics['scheduled_appointments'] ?? 0 }}</p>
                        </div>
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <!-- Completed Appointments Card -->
                <div
                    class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium dark:text-gray-400">{{ __('Completed') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $metrics['completed_appointments'] ?? 0 }}</p>
                        </div>
                        <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd"
                                  d="M4 5a2 2 0 012-2 1 1 0 000-2 4 4 0 00-4 4v10a4 4 0 004 4h12a4 4 0 004-4V5a4 4 0 00-4-4 1 1 0 000 2 2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <!-- New Patients Card -->
                <div
                    class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium dark:text-gray-400">{{ __('New Patients') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $metrics['new_patients'] ?? 0 }}</p>
                        </div>
                        <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                </div>

                <!-- Total Patients Card -->
                <div
                    class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium dark:text-gray-400">{{ __('Total Patients') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $metrics['total_patients'] ?? 0 }}</p>
                        </div>
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 7H7v6h6V7z"/>
                            <path fill-rule="evenodd"
                                  d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1h-2v1a1 1 0 11-2 0v-1H7a2 2 0 01-2-2v-2H4a1 1 0 110-2h1V9H4a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM9 5H7v2h2V5z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="p-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="grid w-full grid-cols-1 gap-4 mb-4 xl:grid-cols-2">
            <!-- Appointment Status Chart -->
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Appointment Status') }}</h3>
                <div class="w-full h-64 flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded">
                    <canvas id="appointmentStatusChart"></canvas>
                </div>
            </div>

            <!-- Patient Growth Chart -->
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Patient Growth') }}</h3>
                <div class="w-full h-64 flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded">
                    <canvas id="patientGrowthChart"></canvas>
                </div>
            </div>

            <!-- Appointments by Doctor -->
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Appointments by Doctor') }}</h3>
                <div class="w-full h-64 flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded">
                    <canvas id="appointmentsByDoctorChart"></canvas>
                </div>
            </div>

            <!-- Insurance Status Chart -->
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Insurance Requests Status') }}</h3>
                <div class="w-full h-64 flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded">
                    <canvas id="insuranceStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Metrics Section -->
    <div class="p-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="grid w-full grid-cols-1 gap-4 xl:grid-cols-2">
            <!-- Pending Appointments -->
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Pending Appointments') }}</h3>
                    <span
                        class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">{{ $metrics['pending_appointments'] ?? 0 }}</span>
                </div>
            </div>

            <!-- Pending Insurance Requests -->
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Pending Insurance Requests') }}</h3>
                    <span
                        class="bg-yellow-100 text-yellow-800 text-sm font-medium px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">{{ $metrics['pending_insurance_requests'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Appointments Section -->
    <div class="p-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Today\'s Appointments') }}</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">{{ __('Time') }}</th>
                    <th scope="col" class="px-6 py-3">{{ __('Patient') }}</th>
                    <th scope="col" class="px-6 py-3">{{ __('Doctor') }}</th>
                    <th scope="col" class="px-6 py-3">{{ __('Status') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($todayAppointments ?? [] as $appointment)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4">{{ $appointment->appointment_date->format('H:i') }}</td>
                        <td class="px-6 py-4">{{ $appointment->patient->name }}</td>
                        <td class="px-6 py-4">{{ $appointment->doctor->name }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 text-xs font-medium rounded-full
                                @if($appointment->status === 'scheduled') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                @elseif($appointment->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="px-6 py-4 text-center text-gray-500">{{ __('No appointments for today') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending Insurance Requests Section -->
    <div class="p-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Pending Insurance Requests') }}</h3>
        <div class="space-y-3">
            @forelse($pendingInsuranceRequests ?? [] as $request)
                <div
                    class="p-4 border border-gray-200 rounded-lg dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $request->appointment->patient->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $request->insuranceCompany->name }}</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white mt-2">{{ __('Amount') }}:
                                ${{ number_format($request->estimated_cost, 2) }}</p>
                        </div>
                        <span
                            class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">{{ ucfirst($request->status) }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    {{ __('No pending insurance requests') }}
                </div>
            @endforelse
        </div>
    </div>

    <!-- Overdue Bills Section -->
    <div class="p-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Overdue Bills') }}</h3>
        <div class="space-y-3">
            @forelse($overdueBills ?? [] as $bill)
                <div
                    class="p-4 border border-red-200 rounded-lg dark:border-red-700 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $bill->patient->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Bill ID') }}:
                                #{{ $bill->id }}</p>
                            <p class="text-sm font-medium text-red-600 dark:text-red-400 mt-2">{{ __('Due') }}:
                                ${{ number_format($bill->balance, 2) }}</p>
                        </div>
                        <span
                            class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">{{ __('Overdue') }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    {{ __('No overdue bills') }}
                </div>
            @endforelse
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // Appointment Status Chart
        const appointmentStatusCtx = document.getElementById('appointmentStatusChart').getContext('2d');
        new Chart(appointmentStatusCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartData['appointmentStatus']['labels'] ?? []) !!},
                datasets: [{
                    data: {!! json_encode($chartData['appointmentStatus']['data'] ?? []) !!},
                    backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Patient Growth Chart
        const patientGrowthCtx = document.getElementById('patientGrowthChart').getContext('2d');
        new Chart(patientGrowthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['patientGrowth']['labels'] ?? []) !!},
                datasets: [{
                    label: '{{ __("New Patients") }}',
                    data: {!! json_encode($chartData['patientGrowth']['data'] ?? []) !!},
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Appointments by Doctor Chart
        const appointmentsByDoctorCtx = document.getElementById('appointmentsByDoctorChart').getContext('2d');
        new Chart(appointmentsByDoctorCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['appointmentsByDoctor']['labels'] ?? []) !!},
                datasets: [{
                    label: '{{ __("Appointments") }}',
                    data: {!! json_encode($chartData['appointmentsByDoctor']['data'] ?? []) !!},
                    backgroundColor: '#3B82F6',
                    borderColor: '#1E40AF',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Insurance Status Chart
        const insuranceStatusCtx = document.getElementById('insuranceStatusChart').getContext('2d');
        new Chart(insuranceStatusCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($chartData['insuranceStatus']['labels'] ?? []) !!},
                datasets: [{
                    data: {!! json_encode($chartData['insuranceStatus']['data'] ?? []) !!},
                    backgroundColor: ['#F59E0B', '#10B981', '#EF4444', '#8B5CF6'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</div>
