<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BranchInventory;
use App\Models\Branch;
use App\Models\Product;

class BranchInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Branch::count() == 0) {
            Branch::factory()->count(5)->create();
        }

        if (Product::count() == 0) {
            Product::factory()->count(10)->create();
        }

        BranchInventory::factory()->count(50)->create();
    }
}
