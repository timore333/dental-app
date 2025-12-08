<x-app-layout title="{{ __('Profile') }}">
    <div class="mb-6">
        <x-breadcrumb :items="[
            ['label' => __('Home'), 'url' => route('dashboard')],
            ['label' => __('Profile')]
        ]" />
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Profile Settings') }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <x-card>
            <x-slot name="header">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Your Profile') }}</h2>
            </x-slot>

            <div class="text-center">
                <div class="mb-4">
                    <div class="inline-flex items-center justify-center h-24 w-24 rounded-full bg-blue-100 dark:bg-blue-900/30">
                        <svg class="h-12 w-12 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ auth()->user()->email }}</p>
                <div class="mt-4">
                    <x-badge variant="info">{{ ucfirst(auth()->user()->role ?? 'user') }}</x-badge>
                </div>
            </div>
        </x-card>

        <!-- Settings Sections -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <x-card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Personal Information') }}</h2>
                </x-slot>

                <form method="POST" action="#" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <x-input
                        type="text"
                        id="name"
                        name="name"
                        label="{{ __('Full Name') }}"
                        :value="auth()->user()->name"
                    />

                    <x-input
                        type="email"
                        id="email"
                        name="email"
                        label="{{ __('Email Address') }}"
                        :value="auth()->user()->email"
                    />

                    <x-input
                        type="tel"
                        id="phone"
                        name="phone"
                        label="{{ __('Phone Number') }}"
                        :value="auth()->user()->phone ?? ''"
                    />

                    <div class="pt-4">
                        <x-button type="submit" variant="primary">
                            {{ __('Save Changes') }}
                        </x-button>
                    </div>
                </form>
            </x-card>

            <!-- Preferences -->
            <x-card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Preferences') }}</h2>
                </x-slot>

                <form method="POST" action="#" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <!-- Language Selection -->
                    <div>
                        <label for="locale" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Language') }}
                        </label>
                        <select
                            id="locale"
                            name="locale"
                            class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-slate-800 text-gray-900 dark:text-white border-gray-300 dark:border-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="en" {{ session('locale', 'en') === 'en' ? 'selected' : '' }}>English</option>
                            <option value="ar" {{ session('locale', 'en') === 'ar' ? 'selected' : '' }}>العربية</option>
                        </select>
                    </div>

                    <!-- Dark Mode Toggle -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-slate-700">
                        <div>
                            <label for="dark_mode" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Dark Mode') }}
                            </label>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                {{ __('Enable dark theme for better night time viewing') }}
                            </p>
                        </div>
                        <button type="button" id="dark-mode-toggle" class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-300 dark:bg-blue-600 transition-colors">
                            <span class="dark:translate-x-6 translate-x-1 inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                        </button>
                    </div>

                    <div class="pt-4">
                        <x-button type="submit" variant="primary">
                            {{ __('Save Preferences') }}
                        </x-button>
                    </div>
                </form>
            </x-card>

            <!-- Security -->
            <x-card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Change Password') }}</h2>
                </x-slot>

                <form method="POST" action="#" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <x-input
                        type="password"
                        id="current_password"
                        name="current_password"
                        label="{{ __('Current Password') }}"
                        placeholder="{{ __('••••••••') }}"
                    />

                    <x-input
                        type="password"
                        id="password"
                        name="password"
                        label="{{ __('New Password') }}"
                        placeholder="{{ __('••••••••') }}"
                    />

                    <x-input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        label="{{ __('Confirm New Password') }}"
                        placeholder="{{ __('••••••••') }}"
                    />

                    <div class="pt-4">
                        <x-button type="submit" variant="primary">
                            {{ __('Update Password') }}
                        </x-button>
                    </div>
                </form>
            </x-card>

            <!-- Danger Zone -->
            <x-card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-red-600 dark:text-red-400">{{ __('Danger Zone') }}</h2>
                </x-slot>

                <div class="space-y-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Once you delete your account, there is no going back. Please be certain.') }}
                    </p>
                    <form method="POST" action="#" onsubmit="return confirm('{{ __('Are you sure? This action cannot be undone.') }}')">
                        @csrf
                        @method('DELETE')
                        <x-button type="submit" variant="danger">
                            {{ __('Delete Account') }}
                        </x-button>
                    </form>
                </div>
            </x-card>
        </div>
    </div>

    <script>
        document.getElementById('dark-mode-toggle')?.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('dark-mode', document.documentElement.classList.contains('dark'));
        });
    </script>
</x-app-layout>
