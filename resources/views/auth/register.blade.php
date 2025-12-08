<x-guest-layout title="{{ __('Register') }}">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <x-input
                type="text"
                id="name"
                name="name"
                label="{{ __('Full Name') }}"
                :value="old('name')"
                required
                autofocus
                :error="$errors->first('name') ?? ''"
                placeholder="{{ __('John Doe') }}"
            />
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <x-input
                type="email"
                id="email"
                name="email"
                label="{{ __('Email') }}"
                :value="old('email')"
                required
                :error="$errors->first('email') ?? ''"
                placeholder="{{ __('your@email.com') }}"
            />
        </div>

        <!-- Phone -->
        <div class="mb-4">
            <x-input
                type="tel"
                id="phone"
                name="phone"
                label="{{ __('Phone Number') }}"
                :value="old('phone')"
                :error="$errors->first('phone') ?? ''"
                placeholder="{{ __('+20 100 000 0000') }}"
            />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input
                type="password"
                id="password"
                name="password"
                label="{{ __('Password') }}"
                required
                :error="$errors->first('password') ?? ''"
                placeholder="{{ __('••••••••') }}"
            />
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <x-input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                label="{{ __('Confirm Password') }}"
                required
                :error="$errors->first('password_confirmation') ?? ''"
                placeholder="{{ __('••••••••') }}"
            />
        </div>

        <!-- Submit Button -->
        <x-button type="submit" variant="primary" full-width>
            {{ __('Create Account') }}
        </x-button>

        <!-- Login Link -->
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                    {{ __('Sign In') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
