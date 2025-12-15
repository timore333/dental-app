<div class="flex items-center gap-4">
    <!-- Theme Toggle -->
    <button
        data-theme-toggle
        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
        title="Toggle theme"
    >
        <i class="fas fa-{{ $this->theme === 'light' ? 'moon' : 'sun' }} text-lg"></i>
    </button>

    <!-- Language Switcher -->
    <div class="flex gap-2 border-l border-gray-300 dark:border-gray-600 pl-4">
        @foreach($this->languages as $code => $label)
            <button
                wire:click="changeLanguage('{{ $code }}')"
                class="px-3 py-2 rounded-lg font-medium text-sm transition-colors
                    {{ $this->language === $code
                        ? 'bg-blue-600 text-white'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- User Menu -->
    <div class="border-l border-gray-300 dark:border-gray-600 pl-4">
        <div class="relative group">
            <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                <img
                    src="{{ auth()->user()?->avatar_url ?? 'https://via.placeholder.com/40' }}"
                    alt="User"
                    class="w-8 h-8 rounded-full"
                >
            </button>

            <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg hidden group-hover:block">
                <a href="/profile" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    Profile
                </a>
                <a href="/settings" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    Settings
                </a>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
