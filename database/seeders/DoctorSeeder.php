<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        // Sample doctors - create or update users first
        $doctors = [
            [
                'name' => 'Dr. Ahmed Hassan',
                'email' => 'ahmed.hassan@dental.com',
                'password' => 'password',
                'role' => 3,
                'license_number' => 'LN-2023-001',
                'specialization' => 'General Dentistry',
                'phone' => '+20-100-123-4567',
                'bio' => 'Experienced general dentist with 10 years of practice',
            ],
            [
                'name' => 'Dr. Fatima Mohamed',
                'email' => 'fatima.mohamed@dental.com',
                'password' => 'password',
                'role' => 3,
                'license_number' => 'LN-2023-002',
                'specialization' => 'Orthodontics',
                'phone' => '+20-100-234-5678',
                'bio' => 'Specialist in braces and teeth alignment',
            ],
            [
                'name' => 'Dr. Karim Ibrahim',
                'email' => 'karim.ibrahim@dental.com',
                'password' => 'password',
                'role' => 3,
                'license_number' => 'LN-2023-003',
                'specialization' => 'Endodontics',
                'phone' => '+20-100-345-6789',
                'bio' => 'Expert in root canal treatment and endodontic procedures',
            ],
        ];

        foreach ($doctors as $doctorData) {
            // Create or get user
            $user = User::firstOrCreate(
                ['email' => $doctorData['email']],
                [
                    'name' => $doctorData['name'],
                    'password' => bcrypt($doctorData['password']),
                    'role_id' => $doctorData['role'],
                    'email_verified_at' => now(),
                ]
            );

            // Create or update doctor record
            Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'license_number' => $doctorData['license_number'],
                    'specialization' => $doctorData['specialization'],
                    'phone' => $doctorData['phone'],
                    'bio' => $doctorData['bio'],
                    'is_active' => true,
                ]
            );
        }
    }
}
