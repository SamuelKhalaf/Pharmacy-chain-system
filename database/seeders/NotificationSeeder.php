<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\Admin;
use App\Models\BranchInventory;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Admin::count() == 0) {
            Admin::factory()->count(5)->create();
        }

        if (BranchInventory::count() == 0) {
            return;
        }

        Notification::factory()->count(50)->create();
    }
}
