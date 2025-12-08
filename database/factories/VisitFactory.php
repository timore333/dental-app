<?php

namespace Database\Factories;

use App\Models\Visit;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitFactory extends Factory
{
    protected $model = Visit::class;

    public function definition(): array
    {
        $patient = Patient::inRandomOrder()->first() ?? Patient::factory()->create();
        $doctor = Doctor::inRandomOrder()->first() ?? Doctor::factory()->create();

        return [
            'appointment_id' => Appointment::inRandomOrder()->first()?->id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'visit_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'chief_complaint' => $this->faker->sentence(),
            'diagnosis' => $this->faker->paragraph(),
            'treatment_notes' => $this->faker->paragraph(),
        ];
    }
}
