<x-guest-layout title="{{ __('Forgot Password') }}">
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <x-alert type="success" dismissible>
            {{ session('status') }}
        </x-alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-6">
            <x-input
                type="email"
                id="email"
                name="email"
                label="{{ __('Email') }}"
                :value="old('email')"
                required
                autofocus
                :error="$errors->first('email') ?? ''"
                placeholder="{{ __('your@email.com') }}"
            />
        </div>

        <!-- Submit Button -->
        <x-button type="submit" variant="primary" full-width>
            {{ __('Email Password Reset Link') }}
        </x-button>

        <!-- Back to Login -->
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                {{ __('Back to Login') }}
            </a>
        </div>
    </form>
</x-guest-layout>
