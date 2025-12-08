<x-app-layout title="{{ __('Dashboard') }}">
    <div class="mb-6">
        <x-breadcrumb :items="[
            ['label' => __('Home'), 'url' => route('dashboard')],
            ['label' => __('Dashboard')]
        ]" />
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Welcome back') }}, {{ auth()->user()->name }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('Here is what\'s happening with your clinic today.') }}</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Patients Card -->
        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('Total Patients') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">0</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 p-4 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10h.01M11 10h.01M7 10h.01M6 20h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <!-- Appointments Today Card -->
        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('Appointments Today') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">0</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 p-4 rounded-lg">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <!-- Revenue Card -->
        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('Revenue This Month') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">EGP 0</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/30 p-4 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <!-- Pending Payments Card -->
        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('Pending Payments') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">0</p>
                </div>
                <div class="bg-red-100 dark:bg-red-900/30 p-4 rounded-lg">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-6a4 4 0 100 8 4 4 0 000-8zm0-1a5 5 0 110 10 5 5 0 010-10z"></path>
                    </svg>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Recent Appointments') }}</h2>
                </x-slot>
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    {{ __('No appointments yet') }}
                </div>
            </x-card>
        </div>

        <div>
            <x-card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Quick Actions') }}</h2>
                </x-slot>
                <div class="space-y-3">
                    <a href="#" class="block p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors text-blue-600 dark:text-blue-400 font-medium text-sm">
                        + {{ __('New Patient') }}
                    </a>
                    <a href="#" class="block p-3 rounded-lg bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors text-green-600 dark:text-green-400 font-medium text-sm">
                        + {{ __('New Appointment') }}
                    </a>
                    <a href="#" class="block p-3 rounded-lg bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors text-purple-600 dark:text-purple-400 font-medium text-sm">
                        + {{ __('New Payment') }}
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
