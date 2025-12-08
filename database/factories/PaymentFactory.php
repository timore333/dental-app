<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $bill = Bill::inRandomOrder()->first() ?? Bill::factory()->create();

        return [
            'bill_id' => $bill->id,
            'amount' => $this->faker->randomFloat(2, 50, min(500, $bill->getBalance())),
            'payment_method' => $this->faker->randomElement(['cash', 'cheque', 'card', 'bank_transfer']),
            'payment_date' => $this->faker->dateTimeBetween('now', '-3 months'),
            'reference_number' => $this->faker->numerify('REF-#####'),
            'receipt_number' => Payment::generateReceiptNumber(),
            'notes' => $this->faker->sentence(),
            'created_by' => User::where('role', 'admin')->first()?->id ?? 1,
        ];
    }
}
