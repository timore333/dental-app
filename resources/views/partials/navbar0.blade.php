<!-- Navigation Bar Component - FULLY CORRECTED v2 -->
<nav class="sticky top-0 z-40 bg-white dark:bg-slate-800 shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-primary">
                    🦷 {{__('App name')}}
                </a>
            </div>

            <!-- Middle Navigation (Desktop) -->
            <div class="hidden md:flex items-center space-x-1">
                @php
                    $navConfig = config('navigation');
                    // ✅ FIXED: Get the role name safely
                    $userRole = auth()->user()?->role?->name ?? 'receptionist';
                    $menuItems = $navConfig[$userRole] ?? $navConfig['receptionist'] ?? [];
                @endphp

                @foreach($menuItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200
        {{ (isset($item['active']) && request()->routeIs($item['active']))
            ? 'bg-primary text-green-600 dark:bg-primary dark:text-green-500 shadow-sm shadow-red-400 dark:shadow-red-900/50 font-semibold'
            : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white' }}">
                        {{ __($item['label']) }}
                    </a>
                @endforeach


            </div>

            <!-- Right Side - User Menu -->
            <div class="flex items-center space-x-4">
                <!-- Language Switcher -->
                <div class="relative" x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        @click.away="open = false"
                        class="px-3 py-1 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md"
                    >
                        {{ strtoupper(app()->getLocale()) }} ▼
                    </button>

                    <div
                        x-show="open"
                        @click.away="open = false"
                        class="absolute right-0 mt-2 w-32 bg-white dark:bg-slate-700 rounded-lg shadow-lg z-50"
                        x-transition
                    >
                        <a
                            href="{{ route('set-locale', 'en') }}"
                            @click="open = false"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600 first:rounded-t-lg"
                        >
                            English
                        </a>
                        <a
                            href="{{ route('set-locale', 'ar') }}"
                            @click="open = false"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600 last:rounded-b-lg"
                        >
                            العربية
                        </a>
                    </div>
                </div>

                <!-- Theme Toggle -->
                <button id="theme-toggle"
                        class="px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md">
                    <span class="dark:hidden">🌙</span>
                    <span class="hidden dark:inline">☀️</span>
                </button>

                <!-- User Dropdown -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            @click.away="open = false"
                            class="flex items-center space-x-2 px-3 py-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">

                            <span
                                class="hidden md:inline text-sm font-medium">{{ auth()->user()?->name ?? 'User' }}</span>
                            <span>▼</span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-700 rounded-lg shadow-lg z-50">
                            <a href="{{ route('profile.edit') }}"
                               @click="open = false"
                               class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600 first:rounded-t-lg">
                                {{ __('Profile') }}
                            </a>
                            <a href="{{ route('settings.index') }}"
                               @click="open = false"
                               class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600">
                                {{ __('Settings') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit"
                                        @click="open = false"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-slate-600 last:rounded-b-lg">
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                    </div>

                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 rounded-lg">
                        {{ __('Login') }}
                    </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-toggle"
                        class="md:hidden px-3 py-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">
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
                <a href="{{ route($item['route']) }}"
                   class="block px-3 py-2 rounded-md text-sm font-medium
                    {{ (isset($item['active']) && request()->routeIs($item['active']))
                        ? 'bg-primary text-white'
                        : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700' }}">
                    {{ __($item['label']) }}
                </a>
            @endforeach
        </div>
    </div>
</nav>

<script>
    // Mobile Menu Toggle
    document.getElementById('mobile-menu-toggle')?.addEventListener('click', function () {
        const menu = document.getElementById('mobile-menu');
        menu?.classList.toggle('hidden');
    });

    // Theme Toggle
    document.getElementById('theme-toggle')?.addEventListener('click', function () {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    });
</script>
