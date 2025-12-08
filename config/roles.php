<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Role and Permission Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file defines the role-based access control (RBAC)
    | system for the dental clinic application.
    |
    */

    'roles' => [
        'Admin' => [
            'description' => 'System administrator with full access',
            'permissions' => [
                'view-dashboard',
                'manage-users',
                'manage-roles',
                'manage-appointments',
                'manage-patients',
                'manage-billing',
                'view-reports',
                'manage-insurance',
                'manage-settings',
            ],
        ],
        'Receptionist' => [
            'description' => 'Reception staff managing appointments and patients',
            'permissions' => [
                'view-dashboard',
                'manage-appointments',
                'manage-patients',
                'manage-billing',
            ],
        ],
        'Doctor' => [
            'description' => 'Medical professional managing patient records',
            'permissions' => [
                'view-dashboard',
                'manage-appointments',
                'manage-patients',
            ],
        ],
        'Accountant' => [
            'description' => 'Financial staff managing billing and reports',
            'permissions' => [
                'view-dashboard',
                'manage-billing',
                'view-reports',
            ],
        ],
    ],

    'permissions' => [
        'view-dashboard' => 'View dashboard',
        'manage-users' => 'Manage users',
        'manage-roles' => 'Manage roles',
        'manage-appointments' => 'Manage appointments',
        'manage-patients' => 'Manage patients',
        'manage-billing' => 'Manage billing',
        'view-reports' => 'View reports',
        'manage-insurance' => 'Manage insurance',
        'manage-settings' => 'Manage settings',
    ],
];
