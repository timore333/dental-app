<div class="max-w-4xl">
    <!-- Tabs -->
    <div class="flex gap-4 border-b border-slate-200 dark:border-slate-700 mb-6">
        <button
            wire:click="$set('tab', 'profile')"
            class="px-4 py-2 font-medium {{ $tab === 'profile' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 dark:text-slate-400' }}"
        >
            {{ __('Personal Info') }}
        </button>
        <button
            wire:click="$set('tab', 'security')"
            class="px-4 py-2 font-medium {{ $tab === 'security' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 dark:text-slate-400' }}"
        >
            {{ __('Security') }}
        </button>
        <button
            wire:click="$set('tab', 'two-factor')"
            class="px-4 py-2 font-medium {{ $tab === 'two-factor' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 dark:text-slate-400' }}"
        >
            {{ __('Two-Factor') }}
        </button>
        <button
            wire:click="$set('tab', 'language')"
            class="px-4 py-2 font-medium {{ $tab === 'language' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 dark:text-slate-400' }}"
        >
            {{ __('Language') }}
        </button>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-800 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    <!-- Personal Info Tab -->
    @if($tab === 'profile')
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 space-y-4">
            <h3 class="text-lg font-semibold mb-4">{{ __('Personal Information') }}</h3>

            <div>
                <label class="block text-sm font-medium mb-2">{{ __('Name') }}</label>
                <input
                    type="text"
                    wire:model="name"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                >
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">{{ __('Email') }}</label>
                <input
                    type="email"
                    wire:model="email"
                    disabled
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-slate-100 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-400"
                >
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Email cannot be changed') }}</p>
            </div>

            <button
                wire:click="updateProfile"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
                {{ __('Save Changes') }}
            </button>
        </div>
    @endif

    <!-- Security Tab -->
    @if($tab === 'security')
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 space-y-4">
            <h3 class="text-lg font-semibold mb-4">{{ __('Change Password') }}</h3>

            <form wire:submit.prevent="changePassword" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Current Password') }}</label>
                    <input
                        type="password"
                        wire:model="currentPassword"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                    >
                    @error('currentPassword') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('New Password') }}</label>
                    <input
                        type="password"
                        wire:model="newPassword"
                        placeholder="Min 8 chars, 1 uppercase, 1 number, 1 special char"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                    >
                    @error('newPassword') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Confirm New Password') }}</label>
                    <input
                        type="password"
                        wire:model="newPasswordConfirmation"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                    >
                </div>

                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                    {{ __('Change Password') }}
                </button>
            </form>
        </div>
    @endif

    <!-- Two-Factor Tab -->
    @if($tab === 'two-factor')
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6">
            @livewire('two-factor-setup')
        </div>
    @endif

    <!-- Language Tab -->
    @if($tab === 'language')
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 space-y-4">
            <h3 class="text-lg font-semibold mb-4">{{ __('Preferences') }}</h3>

            <div>
                <label class="block text-sm font-medium mb-2">{{ __('Language') }}</label>
                <select
                    wire:model.live="language"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                >
                    <option value="en">English</option>
                    <option value="ar">العربية</option>
                </select>
            </div>

            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input
                        type="checkbox"
                        wire:model.live="darkMode"
                        class="rounded"
                    >
                    <span class="text-sm font-medium">{{ __('Dark Mode') }}</span>
                </label>
            </div>

            <button
                wire:click="updateProfile"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
                {{ __('Save Preferences') }}
            </button>
        </div>
    @endif
</div>
