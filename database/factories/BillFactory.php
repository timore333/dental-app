<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\InsuranceCompany;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillFactory extends Factory
{
    protected $model = Bill::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['cash', 'insurance']);
        $totalAmount = $this->faker->randomFloat(2, 100, 2000);
        $paidAmount = $this->faker->randomFloat(2, 0, $totalAmount);


        // Determine status based on paid amount
        if ($paidAmount >= $totalAmount) {
            $status = 'fully_paid';
            $paidAmount = $totalAmount;
        } elseif ($paidAmount > 0) {
            $status = 'partially_paid';
        } else {
            $status = 'issued';
        }

        return [
            'bill_number' => Bill::generateBillNumber(),
            'patient_id' => Patient::inRandomOrder()->first()?->id ?? Patient::factory(),
            'doctor_id' => Doctor::inRandomOrder()->first()?->id,
            'bill_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'type' => $type,
            'insurance_company_id' => $type === 'insurance'
                ? InsuranceCompany::inRandomOrder()->first()?->id
                : null,
            'insurance_request_id' => null,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'status' => $status,
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'notes' => $this->faker->paragraph(),
            'created_by' => User::admin()->first()?->id ?? 1,
        ];
    }
}
