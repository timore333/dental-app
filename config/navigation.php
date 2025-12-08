<?php

return [
    'admin' => [
        [
            'icon' => '📊',
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'active' => 'dashboard',
        ],

        [
            'icon' => '👥',
            'label' => 'Patients',
            'route' => 'patients.index',
            'active' => 'patients.*',
            'sub' => [
                ['label' => 'All Patients', 'route' => 'patients.index'],
                ['label' => 'New Patient', 'route' => 'patients.create'],
            ],
        ],

        [
            'icon' => '📅',
            'label' => 'Appointments',
            'route' => 'appointments.index',
            'active' => 'appointments.*',
            'sub' => [
                ['label' => 'All Appointments', 'route' => 'appointments.index'],
                ['label' => 'New Appointment', 'route' => 'appointments.create'],
            ],
        ],

        [
            'icon' => '🏥',
            'label' => 'Insurance',
            'route' => 'insurance.index',
            'active' => 'insurance.*',
            'sub' => [
                ['label' => 'Companies', 'route' => 'insurance.index'],
                ['label' => 'New Company', 'route' => 'insurance.create'],
            ],
        ],

        [
            'icon' => '🔧',
            'label' => 'Procedures',
            'route' => 'procedures.index',
            'active' => 'procedures.*',
        ],

        [
            'icon' => '💳',
            'label' => 'Payments',
            'route' => 'payments.index',
            'active' => 'payments.*',
        ],

        [
            'icon' => '📈',
            'label' => 'Reports',
            'route' => 'reports.index',
            'active' => 'reports.*',
        ],

        [
            'icon' => '⚙️',
            'label' => 'Settings',
            'route' => 'profile.edit',
            'active' => 'profile.*',
        ],
    ],

    'receptionist' => [
        [
            'icon' => '📊',
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'active' => 'dashboard',
        ],

        [
            'icon' => '👥',
            'label' => 'Patients',
            'route' => 'patients.index',
            'active' => 'patients.*',
        ],

        [
            'icon' => '📅',
            'label' => 'Appointments',
            'route' => 'appointments.index',
            'active' => 'appointments.*',
        ],

        [
            'icon' => '💳',
            'label' => 'Payments',
            'route' => 'payments.index',
            'active' => 'payments.*',
        ],

        [
            'icon' => '⚙️',
            'label' => 'Settings',
            'route' => 'profile.edit',
            'active' => 'profile.*',
        ],
    ],

    'doctor' => [
        [
            'icon' => '📊',
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'active' => 'dashboard',
        ],

        [
            'icon' => '👥',
            'label' => 'Patients',
            'route' => 'patients.index',
            'active' => 'patients.*',
        ],

        [
            'icon' => '📅',
            'label' => 'Appointments',
            'route' => 'appointments.index',
            'active' => 'appointments.*',
        ],

        [
            'icon' => '🔧',
            'label' => 'Procedures',
            'route' => 'procedures.index',
            'active' => 'procedures.*',
        ],

        [
            'icon' => '📈',
            'label' => 'Reports',
            'route' => 'reports.index',
            'active' => 'reports.*',
        ],

        [
            'icon' => '⚙️',
            'label' => 'Settings',
            'route' => 'profile.edit',
            'active' => 'profile.*',
        ],
    ],

    'accountant' => [
        [
            'icon' => '📊',
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'active' => 'dashboard',
        ],

        [
            'icon' => '💳',
            'label' => 'Payments',
            'route' => 'payments.index',
            'active' => 'payments.*',
        ],

        [
            'icon' => '📈',
            'label' => 'Reports',
            'route' => 'reports.index',
            'active' => 'reports.*',
        ],

        [
            'icon' => '🏥',
            'label' => 'Insurance',
            'route' => 'insurance.index',
            'active' => 'insurance.*',
        ],

        [
            'icon' => '⚙️',
            'label' => 'Settings',
            'route' => 'profile.edit',
            'active' => 'profile.*',
        ],
    ],
];
