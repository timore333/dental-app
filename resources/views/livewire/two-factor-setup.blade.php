<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold">{{ __('Two-Factor Authentication') }}</h3>
        <div class="flex items-center gap-2">
            @if($is2FAEnabled)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                    {{ __('Enabled') }}
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                    {{ __('Disabled') }}
                </span>
            @endif
        </div>
    </div>

    @if($is2FAEnabled && !$showQrCode)
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <p class="text-sm text-blue-800 dark:text-blue-200">{{ __('Two-factor authentication is currently enabled. Your account is protected with an authenticator app.') }}</p>
        </div>

        <button
            wire:click="disable2fa"
            wire:confirm="{{ __('Are you sure you want to disable 2FA? This will reduce your account security.') }}"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
        >
            {{ __('Disable 2FA') }}
        </button>

        <div class="mt-4">
            <h4 class="font-semibold mb-2">{{ __('Recovery Codes') }}</h4>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">{{ __('Keep these codes safe. You can use them to access your account if you lose access to your authenticator.') }}</p>

            @if($backupCodes)
                <div class="bg-slate-100 dark:bg-slate-800 rounded p-3 mb-3 font-mono text-xs text-slate-900 dark:text-slate-100">
                    @foreach($backupCodes as $code)
                        {{ $code }}<br>
                    @endforeach
                </div>

                <button
                    wire:click="regenerateBackupCodes"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm"
                >
                    {{ __('Regenerate Codes') }}
                </button>
            @endif
        </div>
    @elseif($showQrCode)
        <div class="space-y-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <p class="text-sm text-blue-800 dark:text-blue-200">{{ __('Step 1: Scan the QR code below with your authenticator app (Google Authenticator, Microsoft Authenticator, Authy, etc.)') }}</p>
            </div>

            <!-- QR Code -->
            <div class="text-center p-6 bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                {!! DNS1D::getBarcodeHTML($secret, 'C128') !!}
                <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">{{ __('Or enter manually:') }}</p>
                <code class="block mt-2 p-2 bg-slate-100 dark:bg-slate-700 rounded text-xs">{{ $secret }}</code>
            </div>

            <!-- Backup Codes -->
            <div>
                <h4 class="font-semibold mb-2">{{ __('Save Your Recovery Codes') }}</h4>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">{{ __('Save these codes in a safe place. You can use them to access your account if you lose your authenticator.') }}</p>
                <div class="bg-slate-100 dark:bg-slate-800 rounded p-3 font-mono text-xs text-slate-900 dark:text-slate-100">
                    @foreach($backupCodes as $code)
                        {{ $code }}<br>
                    @endforeach
                </div>
            </div>

            <!-- Verification -->
            <div>
                <h4 class="font-semibold mb-2">{{ __('Step 2: Enter Verification Code') }}</h4>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">{{ __('Enter the 6-digit code from your authenticator app:') }}</p>

                <form wire:submit.prevent="verifyCode" class="space-y-3">
                    <div>
                        <input
                            type="text"
                            wire:model="verificationCode"
                            placeholder="000000"
                            maxlength="6"
                            inputmode="numeric"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-center text-xl tracking-widest dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                        >
                        @error('verificationCode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-2">
                        <button
                            type="button"
                            wire:click="$set('showQrCode', false)"
                            class="flex-1 px-4 py-2 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700"
                        >
                            {{ __('Cancel') }}
                        </button>
                        <button
                            type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            {{ __('Verify & Enable') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <p class="text-sm text-yellow-800 dark:text-yellow-200">{{ __('Two-factor authentication is not enabled. Enable it to protect your account with an additional security layer.') }}</p>
        </div>

        <button
            wire:click="enable2fa"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        >
            {{ __('Enable 2FA') }}
        </button>
    @endif
</div>
