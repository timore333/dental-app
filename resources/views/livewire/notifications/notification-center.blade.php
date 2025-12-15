<div class="notification-center min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('Notification Center') }}</h1>
                    <p class="text-gray-600 mt-1">
                        {{ __('You have :count unread notifications', ['count' => $unreadCount]) }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <button wire:click="markAllAsRead"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                        {{ __('Mark all read') }}
                    </button>
                    <button wire:click="deleteAllRead"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                        {{ __('Clear read') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Filter Tabs -->
                <div class="flex gap-2 flex-wrap">
                    <button wire:click="updateFilter('all')"
                            class="px-4 py-2 rounded-lg transition {{ $filter === 'all' ? 'bg-teal-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        {{ __('All') }}
                    </button>
                    <button wire:click="updateFilter('unread')"
                            class="px-4 py-2 rounded-lg transition {{ $filter === 'unread' ? 'bg-teal-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        {{ __('Unread') }}
                    </button>
                    <button wire:click="updateFilter('read')"
                            class="px-4 py-2 rounded-lg transition {{ $filter === 'read' ? 'bg-teal-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        {{ __('Read') }}
                    </button>
                </div>

                <!-- Search -->
                <input type="text"
                       wire:model.debounce="search"
                       placeholder="{{ __('Search notifications...') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
        </div>

        <!-- Notifications List -->
        <div class="space-y-3">
            @forelse($notifications as $notification)
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 {{ $notification->read_at ? 'border-gray-300' : 'border-blue-500' }} hover:shadow-lg transition"
                     wire:key="notification-{{ $notification->id }}">

                    <div class="flex justify-between items-start gap-4">
                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="font-bold text-lg text-gray-900">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </h3>
                                @if(!$notification->read_at)
                                    <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                                @endif
                            </div>

                            <p class="text-gray-700 mb-3">
                                {{ $notification->data['message'] ?? '' }}
                            </p>

                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                <span>
                                    <strong>{{ __('Type:') }}</strong>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded text-xs">
                                        {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                                    </span>
                                </span>
                                <span>
                                    <strong>{{ __('Date:') }}</strong>
                                    {{ $notification->created_at->format('M d, Y H:i') }}
                                </span>
                                <span>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col gap-2">
                            @if(!$notification->read_at)
                                <button wire:click="markAsRead('{{ $notification->id }}')"
                                        class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                    {{ __('Mark read') }}
                                </button>
                            @endif
                            <button wire:click="deleteNotification('{{ $notification->id }}')"
                                    class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200 transition">
                                {{ __('Delete') }}
                            </button>
                        </div>
                    </div>

                    <!-- Additional Details if Available -->
                    @if(isset($notification->data['url']))
                        <div class="mt-4 pt-4 border-t">
                            <a href="{{ $notification->data['url'] }}"
                               class="text-teal-500 hover:text-teal-700 font-medium">
                                {{ __('View Details') }} →
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <p class="text-gray-500 text-lg mb-2">{{ __('No notifications found') }}</p>
                    <p class="text-gray-400">
                        @if($search)
                            {{ __('Try adjusting your search') }}
                        @else
                            {{ __('You are all caught up!') }}
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

    <!-- Loading State -->
    <div wire:loading class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 text-center">
            <div class="inline-block animate-spin">
                <svg class="w-8 h-8 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </div>
            <p class="text-gray-600 mt-3">{{ __('Loading...') }}</p>
        </div>
    </div>
</div>
