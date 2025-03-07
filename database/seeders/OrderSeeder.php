<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Branch;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() == 0) {
            User::factory()->count(5)->create();
        }

        if (Branch::count() == 0) {
            Branch::factory()->count(3)->create();
        }

        if (Product::count() == 0) {
            Product::factory()->count(10)->create();
        }

        Order::factory()
            ->count(20)
            ->create()
            ->each(function ($order) {
                OrderItem::factory()
                    ->count(rand(3, 5))
                    ->create(['order_id' => $order->id]);
            });
    }
}
