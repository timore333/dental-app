<div class="bg-white shadow-lg shadow-gray-200 rounded-2xl p-4 mb-6">
    <h3 class="mb-4 text-xl font-bold">General information</h3>


    <div class="grid grid-cols-6 gap-6">
        <div class="col-span-6 sm:col-span-3">
            <label for="first_name"
                   class="block mb-2 text-sm font-medium text-gray-900">  {{ __('First Name') }}</label>
            <input type="text" name="first_name"
                   class="shadow-lg-sm border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                   placeholder="{{ __('Enter first name') }}" required=""
                   id="first_name"
                   wire:model.live="first_name"
            >
            @error('first_name')
            <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-6 sm:col-span-3">
            <label for="lastName"
                   class="block mb-2 text-sm font-medium text-gray-900">{{ __('Last name') }}</label>
            <input type="text" name="lastName"
                   class="shadow-lg-sm border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                   placeholder="{{ __('Enter last name') }}" required=""
                   id="lastName"
                   wire:model.live="last_name"
            >
            @error('lastName')
            <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-6 sm:col-span-3">
            <label for="email"
                   class="block mb-2 text-sm font-medium text-gray-900">{{ __('Email') }}</label>
            <input name="email"
                   class="shadow-lg-sm border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                   placeholder="{{ __('Enter email address') }}" required=""
                   type="email"
                   id="email"
                   wire:model.live="email"
            >
            @error('email')
            <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror

        </div>

        <div class="col-span-6 sm:col-span-3">
            <label for="phone"
                   class="block mb-2 text-sm font-medium text-gray-900">{{ __('Phone') }}</label>
            <input name="phone"
                   class="shadow-lg-sm border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                   placeholder="{{ __('Enter phone number') }}"
                   required=""
                   type="tel"
                   id="phone"
                   wire:model.live="phone"
            >
            @error('phone')
            <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-6 sm:col-span-3">
            <label for="date_of_birth "
                   class="block mb-2 text-sm font-medium text-gray-900">{{ __('Date of Birth') }}</label>
            <input name="date_of_birth "
                   class="shadow-lg-sm border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                   required=""
                   type="date"
                   id="date_of_birth "
                   wire:model.live="date_of_birth"
            >
            @error('date_of_birth ')
            <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>


        <div class="col-span-6 sm:col-span-3">
            <label for="gender"
                   class="block mb-2 text-sm font-medium text-gray-900"> {{ __('Gender') }} </label>
            <select id="gender"
                    wire:model.live="gender" name="gender" required=""
                    class="border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5">

                                                    @foreach($genders as $key => $value)
                                                        <option value="{{ $value }}">{{ $value }}</option>
                                                    @endforeach
            </select>
            @error('gender')
            <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

    </div>

</div>
