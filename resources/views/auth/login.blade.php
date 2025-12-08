<x-guest-layout title="{{ __('Login') }}">
    <form method="POST" action="{{ route('login') }}">
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

        <!-- Remember Me -->
        <div class="mb-6 flex items-center">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                class="rounded dark:bg-slate-700 border-gray-300 dark:border-slate-600 text-blue-600 dark:text-blue-500"
            />
            <label for="remember_me" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Remember me') }}
            </label>
        </div>

        <!-- Submit Button -->
        <x-button type="submit" variant="primary" full-width>
            {{ __('Sign In') }}
        </x-button>

        <!-- Register Link -->
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Don\'t have an account?') }}
                <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                    {{ __('Register') }}
                </a>
            </p>
        </div>

        <!-- Forgot Password Link -->
        @if (Route::has('password.request'))
            <div class="mt-3 text-center">
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                    {{ __('Forgot your password?') }}
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>

