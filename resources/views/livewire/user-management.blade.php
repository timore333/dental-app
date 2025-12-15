<div>
    <div class="block justify-between items-center p-4 mx-4 mt-9 mb-6 bg-white rounded-2xl shadow-xl shadow-gray-200 lg:p-5 sm:flex">
        <div class="mb-1 w-full">
            <div class="mb-4">
                <nav class="flex mb-5" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li class="inline-flex items-center">
                            <a href="#" class="inline-flex items-center text-gray-700 hover:text-gray-900">
                                <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                                Home
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                <a href="#" class="ml-1 rtl:mr-1 text-sm font-medium text-gray-700 hover:text-gray-900 md:ml-2">{{__('Users')}}</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                <span class="ml-1 rtl:mr-1 text-sm font-medium text-gray-400 md:ml-2" aria-current="page">{{__('List')}}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">{{__('All users')}}</h1>
            </div>

            <div class="sm:flex">
                <div class="hidden items-center mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0">
                    <form class="lg:pr-3" wire:submit.prevent="resetSearch">
                        <label for="users-search" class="sr-only">{{__('Search')}}</label>
                        <div class="relative mt-1 lg:w-64 xl:w-96">
                            <input
                                type="text"
                                wire:model.live="search"
                                id="users-search"
                                class="border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                                placeholder="{{__('Search for users')}}">
                        </div>
                    </form>
                    <div class="flex pl-0 mt-3 space-x-1 sm:pl-2 sm:mt-0">
                        <button type="button" wire:click="resetSearch" class="inline-flex justify-center p-1 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100" title="Reset Search">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center {{ app()->isLocale('en') ? 'ml-auto': 'mr-auto'}}  space-x-2 sm:space-x-3">
                    <button
                        type="button"
                        wire:click="openCreateModal"
                        class="inline-flex items-center justify-center text-white rounded-lg bg-gradient-to-br from-pink-500 to-violet-500 shadow-md shadow-gray-300 hover:scale-[1.02] transition-transform text-sm px-4 py-2.5">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        {{__('Add user')}}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200 mx-4 mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th scope="col" class="px-6 py-3 cursor-pointer hover:bg-gray-100" wire:click="sort('name')">
                            <div class="flex items-center">
                                {{__('Name')}}
                                @if($sortBy === 'name')
                                    <svg class="w-3 h-3 ml-1.5 rtl:mr-1.5 " fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zm0 4a1 1 0 000 2h5a1 1 0 000-2H3zm0 4a1 1 0 000 2h4a1 1 0 000-2H3zm11 5a1 1 0 10-2 0v-2.586L9.707 13.293a1 1 0 00-1.414-1.414L13 15.586V13z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer hover:bg-gray-100" wire:click="sort('email')">
                            <div class="flex items-center">
                                {{__('Email')}}
                                @if($sortBy === 'email')
                                    <svg class="w-3 h-3 ml-1.5 rtl:mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zm0 4a1 1 0 000 2h5a1 1 0 000-2H3zm0 4a1 1 0 000 2h4a1 1 0 000-2H3zm11 5a1 1 0 10-2 0v-2.586L9.707 13.293a1 1 0 00-1.414-1.414L13 15.586V13z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3">{{__("Role")}}</th>
                        <th scope="col" class="px-6 py-3">{{__('Status')}}</th>
                        <th scope="col" class="px-6 py-3 text-center">{{__('Actions')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $user->name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 text-xs font-semibold text-gray-700 bg-gray-200 rounded-full">
                                    {{ __($user->role?->name )?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <button
                                        wire:click="openEditModal({{ $user->id }})"
                                       class="inline-flex items-center py-2 px-3 text-sm ml-2 font-medium text-center text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 hover:text-gray-900 hover:scale-[1.02] transition-all">
                                     <svg class="mr-2 rtl:ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>

                                        {{__('Edit')}}
                                    </button>
{{--                                    <button--}}
{{--                                        wire:click="resetPassword({{ $user->id }})"--}}
{{--                                        wire:confirm="Reset password for {{ $user->name }}?"--}}
{{--                                        class="text-sm text-orange-600 hover:text-orange-900 font-medium">--}}
{{--                                        Reset Password--}}
{{--                                    </button>--}}
                                    <button
                                        wire:click="delete({{ $user->id }})"
                                        wire:confirm="Delete user {{ $user->name }}? This action cannot be undone."
                                        class="inline-flex items-center py-2 px-3 text-sm font-medium text-center text-white bg-gradient-to-br from-red-400 to-red-600 rounded-lg shadow-md shadow-gray-300 hover:scale-[1.02] transition-transform">
                                    <svg class="mr-2 rtl:ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>

                                        {{ __('Delete')}}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                    <p>{{__('No users found')}}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between px-6 py-4 border-t">
            <div class="text-sm text-gray-600">
                Showing <strong>{{ $users->firstItem() ?? 0 }}</strong> to <strong>{{ $users->lastItem() ?? 0 }}</strong> of <strong>{{ $users->total() }}</strong> users
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50" wire:click.self="closeModal">
            <div class="relative px-4 w-full max-w-2xl">
                <!-- Modal content -->
                <div class="relative bg-white rounded-2xl shadow-lg">
                    <!-- Modal header -->
                    <div class="flex justify-between items-start p-5 rounded-t border-b">
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ $isEditing ? 'Edit user' : 'Create new user' }}
                        </h3>
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-2xl text-sm p-1.5 ml-auto inline-flex items-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <form wire:submit.prevent="save">
                            <div class="grid grid-cols-6 gap-6">
                                <!-- Name -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                                    <input
                                        type="text"
                                        wire:model="formData.name"
                                        id="name"
                                        class="shadow-lg-sm border {{ $errors->has('formData.name') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                                        placeholder="John Doe">
                                    @error('formData.name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                                    <input
                                        type="email"
                                        wire:model="formData.email"
                                        id="email"
                                        class="shadow-lg-sm border {{ $errors->has('formData.email') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                                        placeholder="example@company.com">
                                    @error('formData.email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Role -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="role_id" class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                                    <select
                                        wire:model="formData.role_id"
                                        id="role_id"
                                        class="shadow-lg-sm border {{ $errors->has('formData.role_id') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5">
                                        <option value="">Select a role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('formData.role_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password (only for creation) -->
                                @if(!$isEditing)
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                                        <input
                                            type="password"
                                            wire:model="formData.password"
                                            id="password"
                                            class="shadow-lg-sm border {{ $errors->has('formData.password') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                                            placeholder="••••••••">
                                        @error('formData.password')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-gray-500 text-xs mt-1">Min 8 characters, 1 uppercase, 1 number, 1 special character</p>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirm Password</label>
                                        <input
                                            type="password"
                                            wire:model="formData.password_confirmation"
                                            id="password_confirmation"
                                            class="shadow-lg-sm border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                                            placeholder="••••••••">
                                    </div>
                                @else
                                    <!-- Optional password update for edit -->
                                    <div class="col-span-6">
                                        <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900">New Password (Leave empty to keep current)</label>
                                        <input
                                            type="password"
                                            wire:model="formData.password"
                                            id="new_password"
                                            class="shadow-lg-sm border {{ $errors->has('formData.password') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                                            placeholder="••••••••">
                                        @error('formData.password')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-gray-500 text-xs mt-1">Min 8 characters, 1 uppercase, 1 number, 1 special character</p>
                                    </div>

                                    <div class="col-span-6">
                                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirm Password</label>
                                        <input
                                            type="password"
                                            wire:model="formData.password_confirmation"
                                            id="password_confirmation"
                                            class="shadow-lg-sm border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                                            placeholder="••••••••">
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Modal footer -->
                    <div class="items-center p-6 rounded-b border-t border-gray-200 flex justify-end space-x-3">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 text-sm px-5 py-2.5 text-center transition-colors">
                            Cancel
                        </button>
                        <button
                            type="button"
                            wire:click="save"
                            class="text-white rounded-lg bg-gradient-to-br from-pink-500 to-violet-500 shadow-md shadow-gray-300 hover:scale-[1.02] transition-transform text-sm px-5 py-2.5 text-center">
                            {{ $isEditing ? 'Update User' : 'Create User' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
