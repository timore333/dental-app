<div class="min-h-screen bg-gray-50 dark:bg-slate-900">
    <!-- Header -->
    <div class="sticky top-0 z-40 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 px-4 py-3">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">✏️ Edit Patient</h1>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto pb-32">
        <div class="max-w-4xl mx-auto px-4 py-6 space-y-6">

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <h3 class="text-red-800 dark:text-red-300 font-semibold mb-2">❌ Validation Errors:</h3>
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm text-red-700 dark:text-red-400">• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form wire:submit="save" class="space-y-6">

                <!-- Section 1: Personal Information -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-gray-200 dark:border-slate-700 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-3 border-b border-gray-200 dark:border-slate-700">
                        👤 Personal Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                First Name <span class="text-red-600">*</span>
                            </label>
                            <input type="text"
                                   wire:model="first_name"
                                   placeholder="Enter first name..."
                                   class="w-full px-3 py-2 border @error('first_name') border-red-500 @else border-gray-300 dark:border-slate-600 @enderror rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Middle Name
                            </label>
                            <input type="text"
                                   wire:model="middle_name"
                                   placeholder="Enter middle name..."
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Last Name <span class="text-red-600">*</span>
                            </label>
                            <input type="text"
                                   wire:model="last_name"
                                   placeholder="Enter last name..."
                                   class="w-full px-3 py-2 border @error('last_name') border-red-500 @else border-gray-300 dark:border-slate-600 @enderror rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Date of Birth
                            </label>
                            <input type="date"
                                   wire:model="date_of_birth"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Gender
                            </label>
                            <select wire:model="gender"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="">Select gender</option>
                                @foreach ($genders as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Contact Information -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-gray-200 dark:border-slate-700 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-3 border-b border-gray-200 dark:border-slate-700">
                        📞 Contact Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Phone Number <span class="text-red-600">*</span>
                            </label>
                            <input type="tel"
                                   wire:model="phone"
                                   placeholder="Enter phone number..."
                                   class="w-full px-3 py-2 border @error('phone') border-red-500 @else border-gray-300 dark:border-slate-600 @enderror rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Email Address
                            </label>
                            <input type="email"
                                   wire:model="email"
                                   placeholder="Enter email address..."
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Location & Job -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-gray-200 dark:border-slate-700 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-3 border-b border-gray-200 dark:border-slate-700">
                        📍 Location & Job
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Address
                            </label>
                            <input type="text"
                                   wire:model="address"
                                   placeholder="Enter address..."
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                City
                            </label>
                            <input type="text"
                                   wire:model="city"
                                   placeholder="Enter city..."
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Country
                            </label>
                            <input type="text"
                                   wire:model="country"
                                   placeholder="Enter country..."
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Job
                            </label>
                            <input type="text"
                                   wire:model="job"
                                   placeholder="Enter job title..."
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Category & Payment -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-gray-200 dark:border-slate-700 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-3 border-b border-gray-200 dark:border-slate-700">
                        🏷️ Category & Payment
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Category <span class="text-red-600">*</span>
                            </label>
                            <select wire:model="category"
                                    class="w-full px-3 py-2 border @error('category') border-red-500 @else border-gray-300 dark:border-slate-600 @enderror rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="">Select category</option>
                                @foreach ($categories as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Payment Type <span class="text-red-600">*</span>
                            </label>
                            <select wire:model="type"
                                    class="w-full px-3 py-2 border @error('type') border-red-500 @else border-gray-300 dark:border-slate-600 @enderror rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                @foreach ($paymentTypes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Status
                            </label>
                            <select wire:model="is_active"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Insurance (if applicable) -->
                @if ($type === 'insurance')
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-gray-200 dark:border-slate-700 p-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-3 border-b border-gray-200 dark:border-slate-700">
                            🏥 Insurance Information
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Insurance Company
                                </label>
                                <input type="text"
                                       wire:model="insurance_company_id"
                                       placeholder="Enter insurance company ID..."
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Insurance Card Number
                                </label>
                                <input type="text"
                                       wire:model="insurance_card_number"
                                       placeholder="Enter card number..."
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Policyholder Name
                                </label>
                                <input type="text"
                                       wire:model="insurance_policyholder"
                                       placeholder="Enter policyholder name..."
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Insurance Expiry Date
                                </label>
                                <input type="date"
                                       wire:model="insurance_expiry_date"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                @error('insurance_expiry_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Section 6: Additional Notes -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-gray-200 dark:border-slate-700 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-3 border-b border-gray-200 dark:border-slate-700">
                        📝 Additional Notes
                    </h2>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Notes
                        </label>
                        <textarea wire:model="notes"
                                  placeholder="Enter additional notes..."
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"></textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-slate-800 border-t border-gray-200 dark:border-slate-700 px-4 py-4 flex gap-3 justify-end">
                    <button type="button"
                            wire:click="cancel"
                            class="px-6 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-slate-600 dark:hover:bg-slate-700 text-gray-900 dark:text-white font-semibold rounded-lg transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors duration-200 flex items-center gap-2">
                        <span>✅ Update Patient</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
