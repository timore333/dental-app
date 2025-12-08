<x-guest-layout title="{{ __('Reset Password') }}">
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-4">
            <x-input
                type="email"
                id="email"
                name="email"
                label="{{ __('Email') }}"
                :value="old('email', $request->email)"
                required
                autofocus
                :error="$errors->first('email') ?? ''"
            />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input
                type="password"
                id="password"
                name="password"
                label="{{ __('New Password') }}"
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
            {{ __('Reset Password') }}
        </x-button>
    </form>
</x-guest-layout>
