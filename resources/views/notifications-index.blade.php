@extends('layouts.app')

@section('title', 'Notifications - Thnaya Clinic')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-8" aria-label="Breadcrumb">
            <ol class="flex space-x-2">
                <li>
                    <a href="{{ route('dashboard') }}" class="text-teal-600 hover:text-teal-700">
                        {{ __('Dashboard') }}
                    </a>
                </li>
                <li class="text-gray-500">/</li>
                <li class="text-gray-600">{{ __('Notifications') }}</li>
            </ol>
        </nav>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-teal-500 text-white">
                        <h3 class="font-bold">{{ __('Notification Stats') }}</h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="text-center">
                            <p class="text-4xl font-bold text-teal-600">{{ $unreadCount }}</p>
                            <p class="text-sm text-gray-600">{{ __('Unread Notifications') }}</p>
                        </div>

                        <div class="border-t pt-4 text-center">
                            <p class="text-3xl font-bold text-gray-900">{{ $notificationsCount }}</p>
                            <p class="text-sm text-gray-600">{{ __('Total Notifications') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <h3 class="font-bold text-gray-900">{{ __('Quick Actions') }}</h3>
                    </div>

                    <div class="p-6 space-y-2">
                        <a href="{{ route('settings.notifications') }}"
                           class="block w-full px-4 py-2 text-center bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition">
                            {{ __('Manage Preferences') }}
                        </a>

                        <button onclick="document.querySelector('[wire\\:click=\"markAllAsRead\"]')?.click()"
                                class="w-full px-4 py-2 text-center border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            {{ __('Mark All Read') }}
                        </button>

                        <button onclick="document.querySelector('[wire\\:click=\"deleteAllRead\"]')?.click()"
                                class="w-full px-4 py-2 text-center border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition">
                            {{ __('Clear Read') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <!-- Notification Center -->
                <livewire:notifications.notification-center />
            </div>
        </div>

        <!-- Empty State Message (shown if no notifications) -->
        @if($notificationsCount === 0)
            <div class="mt-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('You\'re all caught up!') }}</h3>
                <p class="text-gray-600 mb-6">{{ __('No notifications to display right now.') }}</p>
                <a href="{{ route('dashboard') }}" class="inline-block px-6 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition">
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Real-time notification updates
    Echo.private('App.Models.User.' + {{ auth()->id() }})
        .notification((notification) => {
            // Refresh notification count
            Livewire.dispatch('refresh-notifications');
        });
</script>
@endpush
@endsection
