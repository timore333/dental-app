<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Users') }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Manage system users and their roles') }}</p>
        </div>
        <button wire:click="openCreateModal" class="mt-4 sm:mt-0 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            {{ __('Create User') }}
        </button>
    </div>

    <!-- Filters -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <input
                type="text"
                wire:model.live="search"
                placeholder="{{ __('Search users...') }}"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg dark:bg-slate-800 dark:border-slate-600 dark:text-white"
            >
        </div>
        <div>
            <select
                wire:model.live="roleFilter"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg dark:bg-slate-800 dark:border-slate-600 dark:text-white"
            >
                <option value="">{{ __('All Roles') }}</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white dark:bg-slate-800 rounded-lg shadow">
        <table class="w-full text-sm text-slate-900 dark:text-slate-100">
            <thead class="bg-slate-100 dark:bg-slate-700 border-b border-slate-300 dark:border-slate-600">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold cursor-pointer" wire:click="sort('name')">
                        {{ __('Name') }}
                    </th>
                    <th class="px-6 py-3 text-left font-semibold cursor-pointer" wire:click="sort('email')">
                        {{ __('Email') }}
                    </th>
                    <th class="px-6 py-3 text-left font-semibold">{{ __('Role') }}</th>
                    <th class="px-6 py-3 text-left font-semibold cursor-pointer" wire:click="sort('created_at')">
                        {{ __('Created') }}
                    </th>
                    <th class="px-6 py-3 text-left font-semibold">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700">
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ ucfirst($user->role?->name ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <button wire:click="openEditModal({{ $user->id }})" class="px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600">
                                {{ __('Edit') }}
                            </button>
                            <button wire:click="resetPassword({{ $user->id }})" class="px-3 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                {{ __('Reset') }}
                            </button>
                            <button wire:click="delete({{ $user->id }})" wire:confirm="{{ __('Are you sure?') }}" class="px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600">
                                {{ __('Delete') }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-slate-500">{{ __('No users found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $users->links() }}
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-slate-800 rounded-lg p-6 max-w-md w-full mx-4">
                <h2 class="text-xl font-bold mb-4">{{ $isEditing ? __('Edit User') : __('Create User') }}</h2>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">{{ __('Name') }}</label>
                        <input
                            type="text"
                            wire:model="formData.name"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                        >
                        @error('formData.name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">{{ __('Email') }}</label>
                        <input
                            type="email"
                            wire:model="formData.email"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                        >
                        @error('formData.email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">{{ __('Role') }}</label>
                        <select
                            wire:model="formData.role_id"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                        >
                            <option value="">{{ __('Select Role') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('formData.role_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    @if(!$isEditing)
                        <div>
                            <label class="block text-sm font-medium mb-2">{{ __('Password') }}</label>
                            <input
                                type="password"
                                wire:model="formData.password"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                            >
                            @error('formData.password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">{{ __('Confirm Password') }}</label>
                            <input
                                type="password"
                                wire:model="formData.password_confirmation"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                            >
                        </div>
                    @endif

                    <div class="flex gap-2 justify-end">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700"
                        >
                            {{ __('Cancel') }}
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
