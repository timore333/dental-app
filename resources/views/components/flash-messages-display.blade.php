
<div class="fixed top-4 right-4 rtl:right-auto rtl:left-4 z-50 space-y-3 max-w-md w-full px-4">



  @if (session()->has('message'))
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border-l-4 border-green-500 p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-slate-900 dark:text-white font-medium flex-1">{{ $message }}</p>
        </div>
    @endif



    @session('success')
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border-l-4 border-green-500 p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-slate-900 dark:text-white font-medium flex-1">{{ $value }}</p>
        </div>
    @endsession

    @session('error')
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border-l-4 border-red-500 p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-red-500 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-slate-900 dark:text-white font-medium flex-1">{{ $value }}</p>
        </div>
    @endsession

    @session('warning')
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border-l-4 border-yellow-500 p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-yellow-500 dark:text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p class="text-sm text-slate-900 dark:text-white font-medium flex-1">{{ $value }}</p>
        </div>
    @endsession

    @session('info')
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border-l-4 border-blue-500 dark:border-cyan-400 p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-blue-500 dark:text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-slate-900 dark:text-white font-medium flex-1">{{ $value }}</p>
        </div>
    @endsession
</div>

<script>
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.fixed > div').forEach(el => el.remove());
    }, 5000);
</script>
