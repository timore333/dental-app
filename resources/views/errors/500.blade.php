@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-8 text-center">
            <!-- Error Code -->
            <div class="text-6xl font-bold text-red-600 mb-4">500</div>

            <!-- Error Title -->
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">
                {{ __('Server Error') }}
            </h1>

            <!-- Error Message -->
            <p class="text-slate-600 dark:text-slate-400 mb-8">
                {{ __('Something went wrong on our end. We are working to fix it.') }}
            </p>

            <!-- Additional Info -->
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-8">
                <p class="text-sm text-red-800 dark:text-red-200">
                    {{ __('If the problem persists, please contact support@thnaya.dental') }}
                </p>
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-3">
                <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    {{ __('Back to Dashboard') }}
                </a>
                <a href="mailto:support@thnaya.dental" class="px-6 py-2 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                    {{ __('Contact Support') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
