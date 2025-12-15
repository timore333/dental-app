<div class="bg-white shadow-lg shadow-gray-200 rounded-2xl p-4 mb-5 ">

    <h3 class="mb-4 text-xl font-bold">{{ __('Clinical Information') }}</h3>
    <div class="mb-4">
        <label for="category" class="block mb-2 text-sm font-medium text-gray-900">{{ __('Patient Category') }}</label>

        <select id="category" name="category"
                class="border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                wire:model="category" required="">
            @foreach($categories as $key => $value)
                <option value="{{ $value }}">{{ $value }}</option>
            @endforeach
        </select>

        @error('category')
        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
        @enderror

    </div>


    <div class="mb-6">

        <label for="type" class="block mb-2 text-sm font-medium text-gray-900">{{ __('Payment Type') }}</label>
        <select id="type"
                class="border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                wire:model.live="type"
                name="type" required="">
            @foreach($paymentTypes as $key => $value)
                <option value="{{ $value }}">{{ $value }}</option>
            @endforeach
        </select>
         @error('type')
        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>


    <hr class="mb-5">

    @if($type === 'insurance')
        <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2">

            <div class="mb-6">
                <label for="insurance_company_id"
                       class="block mb-2 text-sm font-medium text-gray-900">{{ __('Insurance company') }}</label>
                <select id="insurance_company_id"
                        wire:model="insurance_company_id" name="insurance_company_id" required=""
                        class="border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5">
                    @foreach($insuranceCompanies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
                @error('insurance_company_id')
                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>


            <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2">
                <label for="insurance_card_number"
                       class="block mb-2 text-sm font-medium text-gray-900">{{ __('Insurance card number') }}</label>
                <input name="insurance_card_number"
                       class="shadow-lg-sm border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                       required=""
                       type="text"
                       id="insurance_card_number"
                       wire:model="insurance_card_number"
                >
                @error('insurance_card_number')
                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2">
                <label for="insurance_policyholder"
                       class="block mb-2 text-sm font-medium text-gray-900">{{ __('Policyholder') }}</label>
                <input name="insurance_policyholder"
                       class="shadow-lg-sm border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                       required=""
                       type="text"
                       id="insurance_policyholder"
                       wire:model="insurance_policyholder"
                >
                @error('insurance_policyholder')
                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2">
                <label for="insurance_expiry_date"
                       class="block mb-2 text-sm font-medium text-gray-900">{{ __('Expiry date') }}</label>
                <input name="insurance_expiry_date"
                       class="shadow-lg-sm border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                       required=""
                       type="date"
                       id="insurance_expiry_date"
                       wire:model="insurance_expiry_date"
                >
                @error('insurance_expiry_date')
                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>


        </div>

    @endif


</div>
