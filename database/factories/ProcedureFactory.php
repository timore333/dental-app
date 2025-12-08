<?php

namespace Database\Factories;

use App\Models\Procedure;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProcedureFactory extends Factory
{
    protected $model = Procedure::class;

    public function definition(): array
    {
        $categories = ['consultation', 'filling', 'extraction', 'crown', 'cleaning', 'whitening', 'root_canal', 'implant'];
        $category = $this->faker->randomElement($categories);

        return [
            'code' => $this->faker->unique()->bothify('PROC-##??'),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'default_price' => $this->faker->randomFloat(2, 50, 500),
            'category' => $category,
            'is_active' => true,
        ];
    }
}
