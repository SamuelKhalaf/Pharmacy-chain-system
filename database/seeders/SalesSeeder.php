<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesSeeder extends Seeder
{
    public function run(): void
    {
        Sale::factory(100)->create()->each(function ($sale) {
            $items = SaleItem::factory(rand(2, 5))->create([
                'sale_id' => $sale->id,
            ]);

            // Update total_price after adding items
            $sale->update([
                'total_price' => $items->sum('price'),
            ]);
        });
    }
}
