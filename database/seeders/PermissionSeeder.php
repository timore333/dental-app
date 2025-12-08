<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            ['name' => 'view-dashboard', 'description' => 'View dashboard'],
            ['name' => 'manage-users', 'description' => 'Manage users'],
            ['name' => 'manage-roles', 'description' => 'Manage roles'],
            ['name' => 'manage-appointments', 'description' => 'Manage appointments'],
            ['name' => 'manage-patients', 'description' => 'Manage patients'],
            ['name' => 'manage-billing', 'description' => 'Manage billing'],
            ['name' => 'view-reports', 'description' => 'View reports'],
            ['name' => 'manage-insurance', 'description' => 'Manage insurance'],
            ['name' => 'manage-settings', 'description' => 'Manage settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        // Assign permissions to roles
        $admin = Role::where('name', 'Admin')->first();
        $receptionist = Role::where('name', 'Receptionist')->first();
        $doctor = Role::where('name', 'Doctor')->first();
        $accountant = Role::where('name', 'Accountant')->first();

        // Admin gets all permissions
        if ($admin) {
            $admin->permissions()->sync(Permission::pluck('id')->toArray());
        }

        // Receptionist permissions
        if ($receptionist) {
            $receptionistPerms = Permission::whereIn('name', [
                'view-dashboard',
                'manage-appointments',
                'manage-patients',
                'manage-billing',
            ])->pluck('id')->toArray();
            $receptionist->permissions()->sync($receptionistPerms);
        }

        // Doctor permissions
        if ($doctor) {
            $doctorPerms = Permission::whereIn('name', [
                'view-dashboard',
                'manage-appointments',
                'manage-patients',
            ])->pluck('id')->toArray();
            $doctor->permissions()->sync($doctorPerms);
        }

        // Accountant permissions
        if ($accountant) {
            $accountantPerms = Permission::whereIn('name', [
                'view-dashboard',
                'manage-billing',
                'view-reports',
            ])->pluck('id')->toArray();
            $accountant->permissions()->sync($accountantPerms);
        }
    }
}
