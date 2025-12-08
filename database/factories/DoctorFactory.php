<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::where('role', 'doctor')->inRandomOrder()->first()?->id ?? 1,
            'license_number' => $this->faker->unique()->numerify('DL-###-###'),
            'specialization' => $this->faker->randomElement([
                'General Dentistry',
                'Orthodontics',
                'Endodontics',
                'Prosthodontics',
                'Pediatric Dentistry',
                'Periodontics',
            ]),
            'phone' => $this->faker->phoneNumber(),
            'bio' => $this->faker->sentence(10),
            'is_active' => true,
        ];
    }

    /**
     * Configure factory for general dentist
     */
    public function generalDentist(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'specialization' => 'General Dentistry',
            ];
        });
    }

    /**
     * Configure factory for orthodontist
     */
    public function orthodontist(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'specialization' => 'Orthodontics',
            ];
        });
    }
}
