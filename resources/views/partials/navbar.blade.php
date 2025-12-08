<!-- Navigation Bar Component - FULLY CORRECTED v2 -->
<nav class="sticky top-0 z-40 bg-white dark:bg-slate-800 shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-primary">
                    🦷 Dental Clinic
                </a>
            </div>

            <!-- Middle Navigation (Desktop) -->
            <div class="hidden md:flex items-center space-x-1">
                @php
                    $navConfig = config('navigation');
                    // ✅ FIXED: Get the role name safely
                    $userRole = auth()->user()?->role?->name ?? 'user';
                    $menuItems = $navConfig[$userRole] ?? $navConfig['admin'] ?? [];
                @endphp

                @foreach($menuItems as $item)
                    <a href="{{ $item['route'] }}"
                       class="px-3 py-2 rounded-md text-sm font-medium
                        {{ (isset($item['active']) && request()->routeIs($item['active']))
                            ? 'bg-primary text-white'
                            : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            <!-- Right Side - User Menu -->
            <div class="flex items-center space-x-4">
                <!-- Language Switcher -->
                <div class="relative group">
                    <button class="px-3 py-1 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md">
                        {{ strtoupper(app()->getLocale()) }} ▼
                    </button>
                    <div class="absolute right-0 mt-2 w-32 bg-white dark:bg-slate-700 rounded-lg shadow-lg hidden group-hover:block">
                        <a href="{{ route('set-locale', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600 first:rounded-t-lg">
                            {{ __('English') }}
                        </a>
                        <a href="{{ route('set-locale', 'ar') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600 last:rounded-b-lg">
                            {{ __('العربية') }}
                        </a>
                    </div>
                </div>

                <!-- Theme Toggle -->
                <button id="theme-toggle" class="px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md">
                    <span class="dark:hidden">🌙</span>
                    <span class="hidden dark:inline">☀️</span>
                </button>

                <!-- User Dropdown -->
                @auth
                    <div class="relative group">
                        <button class="flex items-center space-x-2 px-3 py-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">
                            <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-sm font-bold">
                                {{ substr(auth()->user()?->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="hidden md:inline text-sm font-medium">{{ auth()->user()?->name ?? 'User' }}</span>
                            <span>▼</span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-700 rounded-lg shadow-lg hidden group-hover:block">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600 first:rounded-t-lg">
                                {{ __('Profile') }}
                            </a>
                            <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600">
                                {{ __('Settings') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-slate-600 last:rounded-b-lg">
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 rounded-lg">
                        {{ __('Login') }}
                    </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-toggle" class="md:hidden px-3 py-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">
                    ☰
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu (Hidden by default) -->
        <div id="mobile-menu" class="hidden md:hidden py-2 space-y-1">
            @php
                $userRole = auth()->user()?->role?->name ?? 'user';
                $menuItems = $navConfig[$userRole] ?? $navConfig['admin'] ?? [];
            @endphp

            @foreach($menuItems as $item)
                <a href="{{ $item['route'] }}"
                   class="block px-3 py-2 rounded-md text-sm font-medium
                    {{ (isset($item['active']) && request()->routeIs($item['active']))
                        ? 'bg-primary text-white'
                        : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</nav>

<script>
    // Mobile Menu Toggle
    document.getElementById('mobile-menu-toggle')?.addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu?.classList.toggle('hidden');
    });

    // Theme Toggle
    document.getElementById('theme-toggle')?.addEventListener('click', function() {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    });
</script>
