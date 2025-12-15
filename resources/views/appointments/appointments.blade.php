<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ __('Appointments') }}
            </h2>
        </div>
    </x-slot>

    <!-- Main Content Area with proper padding/margin for sidebar -->
    <div class="min-h-screen bg-gray-50 dark:bg-slate-900 py-12">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            <!-- Card wrapper for appointments table -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-slate-700">
                <!-- Livewire component inside card -->
                @livewire('appointments.index')
            </div>
        </div>
    </div>
</x-app-layout>
