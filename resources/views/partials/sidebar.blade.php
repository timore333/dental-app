<!-- Sidebar Component - FULLY CORRECTED -->
<aside class="hidden md:flex flex-col w-64 bg-white dark:bg-slate-800 shadow h-screen sticky top-0">
    <!-- Logo Section -->
    <div class="px-6 py-4 border-b dark:border-slate-700">
        <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-primary flex items-center space-x-2">
            <span>🦷</span>
            <span class="text-xl">{{ config('app.name') }}</span>
        </a>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-auto bg-white dark:bg-slate-800 px-4 py-6">
        @php
            $navConfig = config('navigation');
            // ✅ FIXED: Get the role NAME, not the Role object
            $userRole = auth()->user()->role?->name ?? 'user';
            $menuItems = $navConfig[$userRole] ?? $navConfig['admin'] ?? [];
        @endphp

        @forelse($menuItems as $item)
            <!-- Main Menu Item -->
            <a href="{{ $item['route'] }}"
               class="flex items-center px-4 py-3 mb-2 text-sm font-medium rounded-lg transition
                {{ (isset($item['active']) && request()->routeIs($item['active']))
                    ? 'bg-primary text-white shadow-md'
                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700' }}">

                @if(isset($item['icon']))
                    <span class="mr-3 text-lg">{{ $item['icon'] }}</span>
                @endif

                <span class="flex-1">{{ $item['label'] }}</span>

                @if(isset($item['active']) && request()->routeIs($item['active']))
                    <span class="ml-auto text-lg">→</span>
                @endif

                @if(isset($item['sub']) && count($item['sub']) > 0)
                    <span class="ml-1">▾</span>
                @endif
            </a>

            <!-- Submenu Items (if exists) -->
            @if(isset($item['sub']) && count($item['sub']) > 0)
                <div class="ml-4 space-y-1 mb-2 bg-gray-50 dark:bg-slate-900 rounded-lg p-2">
                    @foreach($item['sub'] as $subItem)
                        <a href="{{ $subItem['route'] }}"
                           class="flex items-center px-3 py-2 text-xs font-medium rounded transition
                            {{ request()->routeIs($subItem['route'])
                                ? 'bg-primary text-white'
                                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                            {{ $subItem['label'] }}
                        </a>
                    @endforeach
                </div>
            @endif
        @empty
            <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                <p class="text-sm">{{ __('No menu items available') }}</p>
            </div>
        @endforelse
    </nav>

    <!-- User Info Section -->
    <div class="px-6 py-4 border-t dark:border-slate-700 bg-gray-50 dark:bg-slate-900">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold text-sm">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                    {{ auth()->user()->name ?? 'User' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                    {{ strtoupper($userRole ?? 'user') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Logout Button -->
    <div class="px-6 py-4 border-t dark:border-slate-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 rounded-lg transition">
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Sidebar (Collapsed) -->
<aside class="md:hidden fixed left-0 top-16 h-screen w-64 bg-white dark:bg-slate-800 shadow transform -translate-x-full transition-transform duration-200 z-30" id="mobile-sidebar">

    <!-- Navigation Menu (Mobile) -->
    <nav class="flex-1 overflow-auto px-4 py-6 h-[calc(100vh-200px)]">
        @php
            $userRole = auth()->user()->role?->name ?? 'user';
            $menuItems = $navConfig[$userRole] ?? $navConfig['admin'] ?? [];
        @endphp

        @forelse($menuItems as $item)
            <!-- Main Menu Item -->
            <a href="{{ $item['route'] }}"
               class="flex items-center px-4 py-3 mb-2 text-sm font-medium rounded-lg transition
                {{ (isset($item['active']) && request()->routeIs($item['active']))
                    ? 'bg-primary text-white'
                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700' }}"
               onclick="document.getElementById('mobile-sidebar').classList.add('-translate-x-full')">

                @if(isset($item['icon']))
                    <span class="mr-3 text-lg">{{ $item['icon'] }}</span>
                @endif

                <span class="flex-1">{{ $item['label'] }}</span>

                @if(isset($item['sub']) && count($item['sub']) > 0)
                    <span class="ml-1">▾</span>
                @endif
            </a>

            <!-- Submenu Items (if exists) -->
            @if(isset($item['sub']) && count($item['sub']) > 0)
                <div class="ml-4 space-y-1 mb-2 bg-gray-50 dark:bg-slate-900 rounded-lg p-2">
                    @foreach($item['sub'] as $subItem)
                        <a href="{{ $subItem['route'] }}"
                           class="flex items-center px-3 py-2 text-xs font-medium rounded transition
                            {{ request()->routeIs($subItem['route'])
                                ? 'bg-primary text-white'
                                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-slate-700' }}"
                           onclick="document.getElementById('mobile-sidebar').classList.add('-translate-x-full')">
                            {{ $subItem['label'] }}
                        </a>
                    @endforeach
                </div>
            @endif
        @empty
            <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                <p class="text-sm">{{ __('No menu items available') }}</p>
            </div>
        @endforelse
    </nav>

    <!-- User Info & Logout (Mobile) -->
    <div class="px-6 py-4 border-t dark:border-slate-700 bg-gray-50 dark:bg-slate-900">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold text-sm">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                    {{ auth()->user()->name ?? 'User' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                    {{ strtoupper($userRole ?? 'user') }}
                </p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Menu Toggle Script -->
<script>
    // Open mobile sidebar
    document.getElementById('mobile-menu-toggle')?.addEventListener('click', function() {
        const sidebar = document.getElementById('mobile-sidebar');
        sidebar?.classList.toggle('-translate-x-full');
    });

    // Close mobile sidebar when clicking outside
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('mobile-sidebar');
        const toggle = document.getElementById('mobile-menu-toggle');
        if (sidebar && toggle && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
            sidebar.classList.add('-translate-x-full');
        }
    });
</script>
