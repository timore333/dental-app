<div x-data="{
    messages: [],
    add(message, type) {
        let id = Date.now();
        this.messages.push({ id, message, type });
        setTimeout(() => { this.remove(id) }, 4000);
    },
    remove(id) {
        this.messages = this.messages.filter(m => m.id !== id);
    }
}"
@notify.window="add($event.detail.message, $event.detail.type)"
class="fixed top-4 right-4 z-50 space-y-2">

    <template x-for="item in messages" :key="item.id">
        <div x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-8"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             :class="{
                'bg-red-500': item.type === 'error',
                'bg-green-500': item.type === 'success',
                'bg-yellow-500': item.type === 'warning',
                'bg-blue-500': item.type === 'info'
             }"
             class="px-6 py-4 rounded-lg shadow-lg text-white max-w-sm">

            <div class="flex items-center justify-between gap-4">
                <p x-text="item.message" class="text-sm"></p>
                <button @click="remove(item.id)" class="text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    </template>
</div>
