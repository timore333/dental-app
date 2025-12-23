<div class="min-h-screen bg-gradient-to-b from-slate-50 to-white dark:from-slate-900 dark:to-slate-800">
    <!-- Header Section -->
    <div class="sticky top-0 z-40 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('patients.index') }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                        <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $patient->full_name }}</h1>
                        <p class="text-slate-600 dark:text-slate-400 mt-1">
                            <span class="font-semibold">File #:</span> {{ $patient->file_number }}
                            <span class="font-semibold">ID #:</span> {{ $patient->id }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($patient->category === 'vip')
                            bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                        @elseif($patient->category === 'exacting')
                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @elseif($patient->category === 'special')
                            bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @else
                            bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200
                        @endif
                    ">
                        {{ $patient->category_icon }} {{ ucfirst($patient->category) }}
                    </span>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($patient->type === 'insurance')
                            bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                        @else
                            bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @endif
                    ">
                        {{ ucfirst($patient->type) }}
                    </span>
                    @if(!$patient->is_active)
                        <span class="px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                            Inactive
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="sticky top-[80px] z-30 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-1 overflow-x-auto">
                @php
                    $tabs = [
                        'personal' => ['label' => __('Personal Information'), 'icon' => '👤'],
                        'insurance' => ['label' => __('Insurance'), 'icon' => '🏥'],
                        'bills' => ['label' => __('Bills'), 'icon' => '📄'],
                        'payments' => ['label' => __('Payments'), 'icon' => '💰'],
                        'appointments' => ['label' => __('Appointments'), 'icon' => '📅'],
                        'medical' => ['label' => __('Medical File'), 'icon' => '📋'],
                    ];
                @endphp

                @foreach($tabs as $key => $tab)
                    <button
                        wire:click="setActiveTab('{{ $key }}')"
                        class="px-6 py-4 font-medium whitespace-nowrap border-b-2 transition
                            @if($activeTab === $key)
                                border-teal-500 text-teal-600 dark:text-teal-400
                            @else
                                border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-300 hover:border-slate-300 dark:hover:border-slate-600
                            @endif
                        "
                    >
                        {{ $tab['icon'] }} {{ $tab['label'] }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Personal Information Tab -->
        @if($activeTab === 'personal')
            <div class="space-y-6" wire:key="personal-tab">
                <!-- Basic Information Card -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">{{ __('Basic Information') }}</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Name -->
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Full Name') }}</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $patient->full_name }}</p>
                            </div>

                            <!-- Email -->
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Email') }}</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">
                                    {{ $patient->email ?? '—' }}
                                </p>
                            </div>

                            <!-- Phone -->
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Phone') }}</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $patient->phone }}</p>
                            </div>

                            <!-- Gender -->
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Gender') }}</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ ucfirst($patient->gender ?? 'N/A') }}</p>
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Date of Birth') }}</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">
                                    @if($patient->date_of_birth)
                                        {{ dateForHumans($patient->date_of_birth, 'short') }} ({{ $patient->age_display }})
                                    @else
                                        —
                                    @endif
                                </p>
                            </div>

                            <!-- Age -->
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Age') }}</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $patient->age_display }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address & Location Card -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">{{ __('Address & Location') }}</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Address -->
                            <div class="md:col-span-2">
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Address') }}</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $patient->address ?? '—' }}</p>
                            </div>

                            <!-- City -->
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('City') }}</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $patient->city ?? '—' }}</p>
                            </div>

                            <!-- Country -->
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Country') }}</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $patient->country ?? '—' }}</p>
                            </div>

                            <!-- Job -->
                            <div class="md:col-span-2">
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Job') }}</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $patient->job ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Card -->
                @if($patient->notes)
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white">{{ __('Notes') }}</h2>
                        </div>
                        <div class="p-6">
                            <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap">{{ $patient->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Insurance Tab -->
        @if($activeTab === 'insurance')
            <div class="space-y-6" wire:key="insurance-tab">
                @if($patient->isInsurance() && $patient->insuranceCompany)
                    <!-- Insurance Company Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white">{{ __('Insurance Company') }}</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Company Name -->
                                <div>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Company Name') }}</p>
                                    <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $patient->insuranceCompany->name }}</p>
                                </div>

                                <!-- Phone -->
                                <div>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Phone') }}</p>
                                    <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $patient->insuranceCompany->phone ?? '—' }}</p>
                                </div>

                                <!-- Email -->
                                <div>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Email') }}</p>
                                    <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $patient->insuranceCompany->email ?? '—' }}</p>
                                </div>

                                <!-- Website -->
                                <div>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Website') }}</p>
                                    <p class="text-lg font-semibold text-slate-900 dark:text-white">
                                        @if($patient->insuranceCompany->website)
                                            <a href="{{ $patient->insuranceCompany->website }}" target="_blank" class="text-teal-600 dark:text-teal-400 hover:underline">
                                                {{ $patient->insuranceCompany->website }}
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Insurance Details Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white">{{ __('Insurance Details') }}</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Card Number -->
                                <div>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Card Number') }}</p>
                                    <p class="text-lg font-semibold text-slate-900 dark:text-white font-mono">{{ $patient->insurance_card_number ?? '—' }}</p>
                                </div>

                                <!-- Policyholder -->
                                <div>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Policyholder') }}</p>
                                    <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $patient->insurance_policyholder ?? '—' }}</p>
                                </div>

                                <!-- Expiry Date -->
                                <div>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Expiry Date') }}</p>
                                    @if($patient->insurance_expiry_date)
                                        <p class="text-lg font-semibold
                                            @if(now()->parse($patient->insurance_expiry_date)->isPast())
                                                text-red-600 dark:text-red-400
                                            @else
                                                text-slate-900 dark:text-white
                                            @endif
                                        ">
                                            {{ dateForHumans($patient->insurance_expiry_date, 'short') }}
                                            @if(now()->parse($patient->insurance_expiry_date)->isPast())
                                                <span class="ml-2 text-sm font-normal text-red-600 dark:text-red-400">(Expired)</span>
                                            @endif
                                        </p>
                                    @else
                                        <p class="text-lg font-semibold text-slate-900 dark:text-white">—</p>
                                    @endif
                                </div>

                                <!-- Status -->
                                <div>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('Status') }}</p>
                                    @if($patient->insuranceCompany->is_active)
                                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            {{ __('Active') }}
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            {{ __('Inactive') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- No Insurance Message -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                        <div class="flex items-center gap-4">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-blue-900 dark:text-blue-100">{{ __('No Insurance') }}</h3>
                                <p class="text-blue-800 dark:text-blue-200 text-sm mt-1">{{ __('This patient pays with cash and has no insurance information.') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Bills Tab -->
        @if($activeTab === 'bills')
            <div wire:key="bills-tab">
                @if($patient->bills->count() > 0)
                    <!-- Bills Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        @php $billStats = $this->getBillStats(); @endphp

                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Total Bills') }}</p>
                                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ $billStats['total_bills'] }}</p>
                                </div>
                                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Total Amount') }}</p>
                                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ formatCurrency($billStats['total_amount']) }}</p>
                                </div>
                                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Paid Amount') }}</p>
                                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ formatCurrency($billStats['paid_amount']) }}</p>
                                </div>
                                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Balance Due') }}</p>
                                    <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ formatCurrency($billStats['balance_due']) }}</p>
                                </div>
                                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M6 7v12a2 2 0 002 2h8a2 2 0 002-2V7M9 3h6a2 2 0 012 2v2H7V5a2 2 0 012-2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bills Table -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white">{{ __('Bills') }}</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-slate-50 dark:bg-slate-700 border-b border-slate-200 dark:border-slate-600">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">{{ __('Bill #') }}</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">{{ __('Date') }}</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">{{ __('Type') }}</th>
                                        <th class="px-6 py-3 text-right text-sm font-semibold text-slate-900 dark:text-white">{{ __('Amount') }}</th>
                                        <th class="px-6 py-3 text-right text-sm font-semibold text-slate-900 dark:text-white">{{ __('Paid') }}</th>
                                        <th class="px-6 py-3 text-right text-sm font-semibold text-slate-900 dark:text-white">{{ __('Due') }}</th>
                                        <th class="px-6 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach($bills as $bill)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                            <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white">
                                                <a href="#" class="text-teal-600 dark:text-teal-400 hover:underline">
                                                    {{ $bill->bill_number }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                                {{ dateForHumans($bill->bill_date, 'short') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                    @if($bill->type === 'insurance')
                                                        bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @else
                                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @endif
                                                ">
                                                    {{ ucfirst($bill->type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-right text-slate-900 dark:text-white font-semibold">
                                                {{ formatCurrency($bill->total_amount) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-right text-green-600 dark:text-green-400 font-semibold">
                                                {{ formatCurrency($bill->paid_amount) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-right text-red-600 dark:text-red-400 font-semibold">
                                                {{ formatCurrency($bill->getBalance()) }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                    @if($bill->status === 'fully_paid')
                                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($bill->status === 'partially_paid')
                                                        bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @elseif($bill->status === 'issued')
                                                        bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @else
                                                        bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @endif
                                                ">
                                                    {{ ucfirst(str_replace('_', ' ', $bill->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($bills->hasPages())
                            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                                {{ $bills->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    <!-- No Bills Message -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                        <div class="flex items-center gap-4">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-blue-900 dark:text-blue-100">{{ __('No Bills') }}</h3>
                                <p class="text-blue-800 dark:text-blue-200 text-sm mt-1">{{ __('This patient has no bills yet.') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Payments Tab -->
        @if($activeTab === 'payments')
            <div wire:key="payments-tab">
                @if($patient->payments->count() > 0)
                    <!-- Payment Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        @php $paymentStats = $this->getPaymentStats(); @endphp

                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Total Payments') }}</p>
                                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ $paymentStats['total_payments'] }}</p>
                                </div>
                                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Total Amount') }}</p>
                                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ formatCurrency($paymentStats['total_amount']) }}</p>
                                </div>
                                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Last Payment') }}</p>
                                    <p class="text-xl font-bold text-slate-900 dark:text-white mt-2">
                                        @if($paymentStats['last_payment'])
                                            {{ dateForHumans($paymentStats['last_payment'], 'short') }}
                                        @else
                                            —
                                        @endif
                                    </p>
                                </div>
                                <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-lg">
                                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payments Table -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white">{{ __('Payments') }}</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-slate-50 dark:bg-slate-700 border-b border-slate-200 dark:border-slate-600">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">{{ __('Receipt #') }}</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">{{ __('Date') }}</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">{{ __('Bill #') }}</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">{{ __('Method') }}</th>
                                        <th class="px-6 py-3 text-right text-sm font-semibold text-slate-900 dark:text-white">{{ __('Amount') }}</th>
                                        <th class="px-6 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach($payments as $payment)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                            <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white">
                                                {{ $payment->receipt_number }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                                {{ dateForHumans($payment->payment_date, 'short') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                @if($payment->bill)
                                                    <a href="#" class="text-teal-600 dark:text-teal-400 hover:underline">
                                                        {{ $payment->bill->bill_number }}
                                                    </a>
                                                @else
                                                    <span class="text-slate-500 dark:text-slate-400">{{ __('Advance') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                    @switch($payment->payment_method)
                                                        @case('cash')
                                                            bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                            @break
                                                        @case('check')
                                                            bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                            @break
                                                        @case('card')
                                                            bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                            @break
                                                        @default
                                                            bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200
                                                    @endswitch
                                                ">
                                                    {{ ucfirst($payment->payment_method) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-right text-slate-900 dark:text-white font-semibold">
                                                {{ formatCurrency($payment->amount) }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                    @if($payment->status === 'completed')
                                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($payment->status === 'pending')
                                                        bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @else
                                                        bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @endif
                                                ">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($payments->hasPages())
                            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                                {{ $payments->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    <!-- No Payments Message -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                        <div class="flex items-center gap-4">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-blue-900 dark:text-blue-100">{{ __('No Payments') }}</h3>
                                <p class="text-blue-800 dark:text-blue-200 text-sm mt-1">{{ __('This patient has no payments yet.') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Appointments Tab -->
        @if($activeTab === 'appointments')
            <div wire:key="appointments-tab">
                @if($patient->appointments->count() > 0)
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white">{{ __('Appointments') }}</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-slate-50 dark:bg-slate-700 border-b border-slate-200 dark:border-slate-600">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">{{ __('Date') }}</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">{{ __('Time') }}</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">{{ __('Doctor') }}</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">{{ __('Notes') }}</th>
                                        <th class="px-6 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach($appointments as $appointment)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                            <td class="px-6 py-4 text-sm text-slate-900 dark:text-white font-semibold">
                                                {{ dateForHumans($appointment->start, 'short') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                                {{ $appointment->start->format('H:i') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                                {{ $appointment->doctor?->name ?? '—' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                                {{ Str::limit($appointment->notes, 30) ?? '—' }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                    @if($appointment->status === 'completed')
                                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($appointment->status === 'scheduled')
                                                        bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @elseif($appointment->status === 'cancelled')
                                                        bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @else
                                                        bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200
                                                    @endif
                                                ">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($appointments->hasPages())
                            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                                {{ $appointments->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    <!-- No Appointments Message -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                        <div class="flex items-center gap-4">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-blue-900 dark:text-blue-100">{{ __('No Appointments') }}</h3>
                                <p class="text-blue-800 dark:text-blue-200 text-sm mt-1">{{ __('This patient has no appointments yet.') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Medical File Tab -->
        @if($activeTab === 'medical')
            <div wire:key="medical-tab">
                @php $medicalHistory = $this->getMedicalHistory(); @endphp

                @if($medicalHistory->count() > 0)
                    <div class="space-y-4">
                        @foreach($medicalHistory as $visit)
                            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                            {{ __('Visit') }} - {{ dateForHumans($visit->visit_date, 'short') }}
                                        </h3>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                            {{ __('Doctor') }}: {{ $visit->doctor?->name ?? '—' }}
                                        </p>
                                    </div>
                                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                                        @if($visit->status === 'completed')
                                            bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @else
                                            bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200
                                        @endif
                                    ">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </div>

                                <!-- Procedures -->
                                @if($visit->procedures->count() > 0)
                                    <div class="mb-4">
                                        <h4 class="font-semibold text-slate-900 dark:text-white mb-3">{{ __('Procedures') }}</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            @foreach($visit->procedures as $procedure)
                                                <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                                                    <p class="font-medium text-slate-900 dark:text-white">{{ $procedure->name }}</p>
                                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                                        {{ $procedure->description ?? '—' }}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Visit Notes -->
                                @if($visit->notes)
                                    <div>
                                        <h4 class="font-semibold text-slate-900 dark:text-white mb-2">{{ __('Notes') }}</h4>
                                        <p class="text-slate-700 dark:text-slate-300 text-sm whitespace-pre-wrap">{{ $visit->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- No Medical History Message -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                        <div class="flex items-center gap-4">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-blue-900 dark:text-blue-100">{{ __('No Medical History') }}</h3>
                                <p class="text-blue-800 dark:text-blue-200 text-sm mt-1">{{ __('This patient has no medical history yet.') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
