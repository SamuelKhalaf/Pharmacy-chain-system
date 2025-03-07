<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Notification;
use App\Models\Admin;
use App\Models\BranchInventory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = BranchInventory::inRandomOrder()->first();

        return [
            'admin_id' => Admin::inRandomOrder()->first()?->id ?? Admin::factory(),
            'data' => json_encode([
                'text' => "Product reached critical level.",
                'product_name' => $product?->product->name ?? 'Sample Product',
                'branch_name' => $product?->branch->name ?? 'Sample Branch',
                'product_quantity' => $product?->quantity ?? 0,
                'critical_level' => $product?->product->critical_level ?? 5,
            ]),
            'is_read' => false,
        ];
    }
}
