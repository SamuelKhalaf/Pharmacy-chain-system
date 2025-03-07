<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Branch;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Role::count() == 0) {
            Role::factory()->count(5)->create();
        }

        if (Branch::count() == 0) {
            Branch::factory()->count(5)->create();
        }

        Admin::factory()->count(10)->create();
    }
}
