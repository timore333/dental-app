 <div class="bg-white shadow-lg shadow-gray-200 rounded-2xl p-4 ">

                <div class="flex items-center">
                    <div
                        class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-white bg-gradient-to-br from-pink-500 to-voilet-500 rounded-lg shadow-md shadow-gray-300">
                        <i class="ni ni-paper-diploma text-lg" aria-hidden="true"></i>
                    </div>
                    <div class="flex-shrink-0 ml-3">
                        <span
                            class="text-2xl font-bold leading-none text-gray-900">+{{ $metrics['completed_appointments'] ?? 0 }}</span>
                        <h3 class="text-base font-normal text-gray-500">{{ __('Completed') }}</h3>
                    </div>
                    <div class="flex flex-1 justify-end items-center ml-5 w-0 text-base font-bold text-red-500">
                        -2%
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

            </div>
