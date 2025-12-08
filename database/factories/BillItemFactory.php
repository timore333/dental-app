<?php

namespace Database\Factories;

use App\Models\BillItem;
use App\Models\Bill;
use App\Models\Procedure;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillItemFactory extends Factory
{
    protected $model = BillItem::class;

    public function definition(): array
    {
        $procedure = Procedure::inRandomOrder()->first() ?? Procedure::factory()->create();
        $quantity = $this->faker->numberBetween(1, 3);
        $unitPrice = $this->faker->randomFloat(2, 50, 500);
        $totalPrice = $quantity * $unitPrice;

        return [
            'bill_id' => Bill::inRandomOrder()->first()?->id ?? Bill::factory(),
            'procedure_id' => $procedure->id,
            'description' => $procedure->name,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
        ];
    }
}
