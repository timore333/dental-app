<div class="notification-preferences space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Notification Preferences') }}</h1>
        <p class="text-gray-600 mt-2">{{ __('Manage how you receive notifications from Thnaya Clinic') }}</p>
    </div>

    <!-- Success Message -->
    @if($successMessage)
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
            <div class="flex">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="ml-3 text-green-700">{{ $successMessage }}</p>
            </div>
        </div>
    @endif

    <!-- Notification Channels -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h2 class="text-xl font-bold text-gray-900">{{ __('Notification Channels') }}</h2>
            <p class="text-gray-600 text-sm mt-1">{{ __('Choose how you want to receive notifications') }}</p>
        </div>

        <div class="p-6 space-y-4">
            <!-- SMS -->
            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ __('SMS Messages') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('Receive notifications via SMS') }}</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center">
                    <input type="checkbox"
                           wire:model="smsEnabled"
                           class="form-checkbox h-5 w-5 text-teal-500 rounded">
                </label>
            </div>

            <!-- Email -->
            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ __('Email') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('Receive notifications via email') }}</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center">
                    <input type="checkbox"
                           wire:model="emailEnabled"
                           class="form-checkbox h-5 w-5 text-teal-500 rounded">
                </label>
            </div>

            <!-- In-App -->
            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.852l.015.148L13 15a1 1 0 01-1.933.154L11 15V3a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ __('In-App Notifications') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('Receive in-app notifications') }}</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center">
                    <input type="checkbox"
                           wire:model="inAppEnabled"
                           class="form-checkbox h-5 w-5 text-teal-500 rounded">
                </label>
            </div>
        </div>
    </div>

    <!-- Notification Types -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h2 class="text-xl font-bold text-gray-900">{{ __('Notification Types') }}</h2>
            <p class="text-gray-600 text-sm mt-1">{{ __('Select which types of notifications you want to receive') }}</p>
        </div>

        <div class="p-6 space-y-4">
            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 transition cursor-pointer">
                <input type="checkbox"
                       wire:model="appointmentReminders"
                       class="form-checkbox h-5 w-5 text-teal-500 rounded">
                <span class="ml-3 font-medium text-gray-900">{{ __('Appointment Reminders') }}</span>
            </label>

            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 transition cursor-pointer">
                <input type="checkbox"
                       wire:model="paymentNotifications"
                       class="form-checkbox h-5 w-5 text-teal-500 rounded">
                <span class="ml-3 font-medium text-gray-900">{{ __('Payment Notifications') }}</span>
            </label>

            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 transition cursor-pointer">
                <input type="checkbox"
                       wire:model="insuranceNotifications"
                       class="form-checkbox h-5 w-5 text-teal-500 rounded">
                <span class="ml-3 font-medium text-gray-900">{{ __('Insurance Notifications') }}</span>
            </label>

            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 transition cursor-pointer">
                <input type="checkbox"
                       wire:model="promotionalNotifications"
                       class="form-checkbox h-5 w-5 text-teal-500 rounded">
                <span class="ml-3 font-medium text-gray-900">{{ __('Promotional Offers') }}</span>
            </label>

            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 transition cursor-pointer">
                <input type="checkbox"
                       wire:model="marketingSms"
                       class="form-checkbox h-5 w-5 text-teal-500 rounded">
                <span class="ml-3 font-medium text-gray-900">{{ __('Marketing SMS') }}</span>
            </label>
        </div>
    </div>

    <!-- Quiet Hours -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h2 class="text-xl font-bold text-gray-900">{{ __('Quiet Hours') }}</h2>
            <p class="text-gray-600 text-sm mt-1">{{ __('Set times when you don\'t want to receive notifications') }}</p>
        </div>

        <div class="p-6 grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Start Time') }}</label>
                <input type="time"
                       wire:model="quietHoursStart"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('End Time') }}</label>
                <input type="time"
                       wire:model="quietHoursEnd"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
        </div>
    </div>

    <!-- Email Frequency -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h2 class="text-xl font-bold text-gray-900">{{ __('Email Frequency') }}</h2>
            <p class="text-gray-600 text-sm mt-1">{{ __('How often you want to receive email digests') }}</p>
        </div>

        <div class="p-6">
            <select wire:model="emailFrequency"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="immediately">{{ __('Immediately') }}</option>
                <option value="daily">{{ __('Daily Digest') }}</option>
                <option value="weekly">{{ __('Weekly Digest') }}</option>
                <option value="never">{{ __('Never') }}</option>
            </select>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-3 justify-end">
        <button wire:click="resetToDefaults"
                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            {{ __('Reset to Defaults') }}
        </button>

        <div class="flex gap-2">
            <button wire:click="disableAll"
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                {{ __('Disable All') }}
            </button>
            <button wire:click="enableAll"
                    class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                {{ __('Enable All') }}
            </button>
        </div>

        <button wire:click="save"
                class="px-6 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition font-medium">
            {{ __('Save Preferences') }}
        </button>
    </div>
</div>
