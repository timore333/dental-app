<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@test.com',
                'password' => 'aaaaaaaa',
                'role_name' => 'Admin',
            ],
            [
                'name' => 'Receptionist User',
                'email' => 'receptionist@test.com',
                'password' => 'aaaaaaaa',
                'role_name' => 'Receptionist',
            ],
            [
                'name' => 'Doctor User',
                'email' => 'doctor@test.com',
                'password' => 'aaaaaaaa',
                'role_name' => 'Doctor',
            ],
            [
                'name' => 'Accountant User',
                'email' => 'accountant@test.com',
                'password' => 'aaaaaaaa',
                'role_name' => 'Accountant',
            ],
        ];

        foreach ($users as $userData) {
            $role = Role::where('name', $userData['role_name'])->first();

            User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'role_id' => $role?->id,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
