<div
     x-cloak
    x-data="{
        show: false,
        message: '',
        type: 'success',
        timeoutId: null
    }"

    x-on:toast.window="
        message = $event.detail.message;
        type = $event.detail.type ?? 'success';
        show = true;

        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => show = false, 10000);
    "

    x-show="show"

    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"

    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-4"

    class="fixed bottom-6 end-6 z-50 max-w-sm w-50 h-50 rounded-2xl px-5 py-4 text-white shadow-xl pointer-events-none"
    :class="{
        'bg-gradient-to-br from-green-500 to-green-700': type === 'success',
        'bg-red-600': type === 'error',
        'bg-yellow-500': type === 'warning',
        'bg-blue-600': type === 'info'
    }"
>
    <p class="text-sm leading-relaxed" x-text="message">this is a staic text for testing</p>
</div>
