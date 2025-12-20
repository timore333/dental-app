<div
    x-cloak
    x-data="{
        open: false,
        message: '',
        action: null
    }"

    x-on:confirm.window="
        open = true;
        message = $event.detail.message;
        action = $event.detail.action;
    "

    x-show="open"
    x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 pointer-events-none"
>
    <div
        class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl"
        @click.outside="open = false"
    >
        <p class="mb-6 text-sm text-gray-800 text-start" x-text="message"></p>

        <div class="flex justify-end gap-3">
            <button
                @click="open = false"
                class="rounded-xl bg-gray-200 px-4 py-2 text-sm"
            >
                {{ __('Cancel') }}
            </button>

            <button
                @click="$wire[action](); open = false"
                class="rounded-xl bg-red-600 px-4 py-2 text-sm text-white"
            >
                {{ __('Confirm') }}
            </button>
        </div>
    </div>
</div>
