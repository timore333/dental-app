<!-- Notification Panel Component -->
<div class="relative">
    <!-- Notification Bell Icon -->
    <button
        @click="$wire.togglePanel()"
        class="relative p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>

        <!-- Badge showing unread count -->
        @if ($unreadCount > 0)
            <span class="absolute top-1 right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Panel -->
    @if ($isOpen)
        <div class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl z-50">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    {{ __('Notifications') }}
                </h3>
                @if ($unreadCount > 0)
                    <button
                        wire:click="markAllAsRead"
                        class="text-sm text-blue-600 hover:text-blue-700"
                    >
                        {{ __('Mark all as read') }}
                    </button>
                @endif
            </div>

            <!-- Filter Tabs -->
            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 flex gap-2 overflow-x-auto">
                <button
                    wire:click="filterByType('all')"
                    class="px-3 py-1 text-sm font-medium rounded {{ $filterType === 'all' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}"
                >
                    {{ __('All') }}
                </button>
                <button
                    wire:click="filterByType('appointment')"
                    class="px-3 py-1 text-sm font-medium rounded {{ $filterType === 'appointment' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}"
                >
                    {{ __('Appointments') }}
                </button>
                <button
                    wire:click="filterByType('payment')"
                    class="px-3 py-1 text-sm font-medium rounded {{ $filterType === 'payment' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}"
                >
                    {{ __('Payments') }}
                </button>
                <button
                    wire:click="filterByType('insurance')"
                    class="px-3 py-1 text-sm font-medium rounded {{ $filterType === 'insurance' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}"
                >
                    {{ __('Insurance') }}
                </button>
            </div>

            <!-- Notifications List -->
            <div class="max-h-96 overflow-y-auto">
                @if ($notifications)
                    @foreach ($notifications as $notification)
                        <div class="p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <div class="flex justify-between items-start gap-3">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $notification['data']['title'] ?? 'Notification' }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $notification['data']['message'] ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                                    </p>
                                </div>
                                <button
                                    wire:click="delete('{{ $notification['id'] }}')"
                                    class="text-gray-400 hover:text-red-600"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-8 text-center">
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ __('No notifications') }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="p-3 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    {{ __('View all') }} →
                </a>
            </div>
        </div>
    @endif
</div>

<script>
    // Refresh notifications every 30 seconds
    setInterval(() => {
        @this.loadNotifications();
    }, 30000);

    // Close panel when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('[notification-panel]')) {
            @this.isOpen = false;
        }
    });
</script>
