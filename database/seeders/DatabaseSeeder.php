<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            BranchSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            BranchInventorySeeder::class,
            SalesSeeder::class,
            OrderSeeder::class,
            TransferRequestSeeder::class,
            NotificationSeeder::class,
            RoleSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
