<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TransferRequest;
use App\Models\Branch;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransferRequest>
 */
class TransferRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransferRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        do {
            $fromBranch = Branch::inRandomOrder()->first()?->id ?? Branch::factory();
            $toBranch = Branch::inRandomOrder()->first()?->id ?? Branch::factory();
        } while ($fromBranch === $toBranch);

        return [
            'from_branch_id' => $fromBranch,
            'to_branch_id' => $toBranch,
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 50),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
