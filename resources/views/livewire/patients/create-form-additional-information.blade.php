<div class="bg-white shadow-lg shadow-gray-200 rounded-2xl p-4  mb-6">

    <h3 class="mb-4 text-xl font-bold">General Information</h3>

    <div class="mb-4">
        <label for="first-name"
               class="block mb-2 text-sm font-medium text-gray-900"> {{ __('Address') }}</label>
        <input type="text" name="address"
               class="shadow-lg-sm border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
               id="address"
               wire:model.live="address"
               placeholder="{{ __('Enter address') }}"
        >
        @error('address')
        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="col-span-1">
        <label for="notes" class="block mb-2 text-sm font-medium text-gray-900"> {{ __('Notes') }}</label>
        <textarea  name="notes" rows="3"
                  class="block p-4 w-full text-gray-900 rounded-lg border border-gray-300 sm:text-sm focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300"

                  id="notes"
                  wire:model.live="notes"

                  placeholder="{{ __('Enter any additional notes') }}"
        ></textarea>

    </div>

  <div class="col-span-6 sm:col-full mt-5">
            <button
                type="button"
                wire:click="cancel"
                class="text-white bg-gradient-to-br from-pink-500 to-voilet-500 rounded-lg shadow-md shadow-gray-300
                hover:scale-[1.02] transition-transform font-medium text-sm px-5 py-2.5 text-center"
            >
                {{ __('Cancel') }}
            </button>


            <button
                type="button"
                 wire:click="save"
                class="text-white bg-gradient-to-br from-pink-500 to-voilet-500 rounded-lg shadow-md shadow-gray-300
                hover:scale-[1.02] transition-transform font-medium text-sm px-5 py-2.5 text-center"
            >
                <span >{{ __('Create patient') }}</span>

            </button>

        </div>
</div>


