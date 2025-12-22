<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Procedures') }}</h1>
            <p class="text-gray-600 dark:text-gray-400">{{ __('Manage dental procedures and pricing') }}</p>
        </div>
        <button @click="$wire.openCreateModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('Add Procedure') }}
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <input
                    wire:model.live="search"
                    type="text"
                    placeholder="{{ __('Search by name or code...') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <!-- Category Filter -->
            <div>
                <select
                    wire:model.live="category"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ __($cat) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <select
                    wire:model.live="status"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">{{ __('All Status') }}</option>
                    <option value="active">{{ __('Active') }}</option>
                    <option value="inactive">{{ __('Inactive') }}</option>
                </select>
            </div>

            <!-- Reset -->
            <button
                wire:click="$reset"
                class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
            >
                {{ __('Reset Filters') }}
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <button @click="$wire.sort('code')" class="inline-flex items-center font-semibold text-gray-900 dark:text-white hover:text-blue-600">
                                {{ __('Code') }}
                                @if($sortBy === 'code')
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        @if($sortDirection === 'asc')
                                            <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V15a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        @else
                                            <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V5a1 1 0 012 0v9.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        @endif
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button @click="$wire.sort('name')" class="inline-flex items-center font-semibold text-gray-900 dark:text-white hover:text-blue-600">
                                {{ __('Name') }}
                                @if($sortBy === 'name')
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        @if($sortDirection === 'asc')
                                            <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V15a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        @else
                                            <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V5a1 1 0 012 0v9.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        @endif
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">{{ __('Category') }}</th>
                        <th class="px-6 py-3 text-left">
                            <button @click="$wire.sort('default_price')" class="inline-flex items-center font-semibold text-gray-900 dark:text-white hover:text-blue-600">
                                {{ __('Default Price') }}
                                @if($sortBy === 'default_price')
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        @if($sortDirection === 'asc')
                                            <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V15a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        @else
                                            <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V5a1 1 0 012 0v9.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        @endif
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($procedures as $procedure)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $procedure->code }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $procedure->name }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                    {{ __($procedure->category) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">
                                {{ number_format($procedure->default_price, 2) }} EGP
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($procedure->is_active)
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        {{ __('Active') }}
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                        {{ __('Inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="$wire.openEditModal({{ $procedure->id }})"
                                        class="inline-flex items-center px-3 py-1 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="confirm('{{ __('Are you sure?') }}') && $wire.deleteProcedure({{ $procedure->id }})"
                                        class="inline-flex items-center px-3 py-1 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-gray-600 dark:text-gray-400">{{ __('No procedures found') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $procedures->links() }}
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Add New Procedure') }}</h2>
                        <button @click="$wire.closeCreateModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <livewire:procedures.create @close="$wire.closeCreateModal()" />
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal && $editingProcedure)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Edit Procedure') }}</h2>
                        <button @click="$wire.closeEditModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <livewire:procedures.update :procedure="$editingProcedure" :key="$editingProcedure->id" @close="$wire.closeEditModal()" />
                </div>
            </div>
        </div>
    @endif
</div>
