<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        return [
            'branch_id' => Branch::inRandomOrder()->first()->id ?? Branch::factory(),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'total_price' => 0, // Will be updated after adding SaleItems
            'created_at' => $this->faker->dateTimeThisYear,
        ];
    }
}
