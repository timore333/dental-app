@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-8 text-center">
            <!-- Error Code -->
            <div class="text-6xl font-bold text-orange-600 mb-4">404</div>

            <!-- Error Title -->
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">
                {{ __('Page Not Found') }}
            </h1>

            <!-- Error Message -->
            <p class="text-slate-600 dark:text-slate-400 mb-8">
                {{ __('The page you are looking for does not exist or has been moved.') }}
            </p>

            <!-- Additional Info -->
            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4 mb-8">
                <p class="text-sm text-orange-800 dark:text-orange-200">
                    {{ __('Check the URL and try again.') }}
                </p>
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-3">
                <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    {{ __('Back to Dashboard') }}
                </a>
                <a href="javascript:history.back()" class="px-6 py-2 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                    {{ __('Go Back') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
