<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::inRandomOrder()->first()?->id ?? Patient::factory(),
            'doctor_id' => Doctor::inRandomOrder()->first()?->id ?? Doctor::factory(),
            'appointment_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            'status' => $this->faker->randomElement(['scheduled', 'completed', 'cancelled', 'no-show']),
            'reason' => $this->faker->sentence(),
            'notes' => $this->faker->paragraph(),
            'created_by' => User::admin()->first()?->id ?? 1,
        ];
    }

    /**
     * Configure factory for scheduled appointments
     */
    public function scheduled(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'scheduled',
                'appointment_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            ];
        });
    }

    /**
     * Configure factory for completed appointments
     */
    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
                'appointment_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
            ];
        });
    }
}
