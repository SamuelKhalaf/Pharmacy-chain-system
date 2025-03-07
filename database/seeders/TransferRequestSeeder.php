<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransferRequest;
use App\Models\Branch;
use App\Models\Product;

class TransferRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Branch::count() < 2) {
            Branch::factory()->count(3)->create();
        }

        if (Product::count() == 0) {
            Product::factory()->count(10)->create();
        }

        TransferRequest::factory()->count(30)->create();
    }
}
