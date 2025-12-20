<?php

return [
    'admin' => [
        [
            'label' => 'Dashboard',
            'icon' => 'heroicon-s-user',
            'route' => 'dashboard',
            'sub' => false,
            'active' => 'dashboard',
            'background-color' => 'bg-fuchsia-500',
        ],

        [
            'label' => 'Users',
            'icon' => 'heroicon-s-user',
            'route' => 'admin.users.index',
            'sub' => false,
            'active' => 'users.*',
        ],

        [
            'label' => 'Patients',
            'icon' => 'heroicon-o-user',
            'route' => 'admin.patients.index',
            'sub' => true,
            'active' => 'patients.*',
            'sub-items' => [
                ['label' => 'All Patients', 'route' => 'patients.index'],
                ['label' => 'New Patient', 'route' => 'patients.create'],
            ],
        ],

        [
            'icon' => '📅',
            'label' => 'Appointments',
            'route' => 'appointments.index',
            'sub' => true,
            'active' => 'appointments.*',
            'sub-items' => [
                ['label' => 'appointments', 'route' => 'appointments.index'],
                ['label' => 'calendar', 'route' => 'appointments.calendar'],

            ],
        ],

        [
            'icon' => '🏥',
            'label' => 'Insurance',
            'route' => 'insurance.index',
            'sub' => true,
            'active' => 'insurance.*',
            'sub-items' => [
                ['label' => 'Companies', 'route' => 'insurance.index'],
                ['label' => 'New Company', 'route' => 'insurance.create'],
            ],
        ],

        [
            'icon' => '🔧',
            'label' => 'Procedures',
            'route' => 'procedures.index',
            'sub' => false,
            'active' => 'procedures.*',
        ],

        [
            'icon' => '💳',
            'label' => 'Payments',
            'route' => 'payments.index',
            'sub' => false,
            'active' => 'payments.*',
        ],

        [
            'icon' => '📈',
            'label' => 'Reports',
            'route' => 'reports.index',
            'sub' => false,
            'active' => 'reports.*',
        ],

        [
            'icon' => '⚙️',
            'label' => 'Settings',
            'route' => 'profile.edit',
            'sub' => false,
            'active' => 'profile.*',
        ],
    ],

    'receptionist' => [
        [
            'icon' => '📊',
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'sub' => false,
            'active' => 'dashboard',
        ],

        [
            'icon' => '👥',
            'label' => 'Patients',
            'route' => 'patients.index',
            'sub' => false,
            'active' => 'patients.*',
        ],

        [
            'icon' => '📅',
            'label' => 'Appointments',
            'route' => 'appointments.index',
            'sub' => false,
            'active' => 'appointments.*',
        ],

        [
            'icon' => '💳',
            'label' => 'Payments',
            'route' => 'payments.index',
            'sub' => false,
            'active' => 'payments.*',
        ],

        [
            'icon' => '⚙️',
            'label' => 'Settings',
            'route' => 'profile.edit',
            'sub' => false,
            'active' => 'profile.*',
        ],
    ],

    'doctor' => [
        [
            'icon' => '📊',
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'sub' => false,
            'active' => 'dashboard',
        ],

        [
            'icon' => '👥',
            'label' => 'Patients',
            'route' => 'patients.index',
            'sub' => false,
            'active' => 'patients.*',
        ],

        [
            'icon' => '📅',
            'label' => 'Appointments',
            'route' => 'appointments.index',
            'sub' => false,
            'active' => 'appointments.*',
        ],

        [
            'icon' => '🔧',
            'label' => 'Procedures',
            'route' => 'procedures.index',
            'sub' => false,
            'active' => 'procedures.*',
        ],

        [
            'icon' => '📈',
            'label' => 'Reports',
            'route' => 'reports.index',
            'sub' => false,
            'active' => 'reports.*',
        ],

        [
            'icon' => '⚙️',
            'label' => 'Settings',
            'route' => 'profile.edit',
            'sub' => false,
            'active' => 'profile.*',
        ],
    ],

    'accountant' => [
        [
            'icon' => '📊',
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'sub' => false,
            'active' => 'dashboard',
        ],

        [
            'icon' => '💳',
            'label' => 'Payments',
            'route' => 'payments.index',
            'sub' => false,
            'active' => 'payments.*',
        ],

        [
            'icon' => '📈',
            'label' => 'Reports',
            'route' => 'reports.index',
            'sub' => false,
            'active' => 'reports.*',
        ],

        [
            'icon' => '🏥',
            'label' => 'Insurance',
            'route' => 'insurance.index',
            'sub' => false,
            'active' => 'insurance.*',
        ],

        [
            'icon' => '⚙️',
            'label' => 'Settings',
            'route' => 'profile.edit',
            'sub' => false,
            'active' => 'profile.*',
        ],
    ],
];
