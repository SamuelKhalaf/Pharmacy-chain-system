<?php

namespace Database\Seeders;

use App\Enums\AdminRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => AdminRole::SuperAdmin->value, 'name' => 'Super Admin'],
            ['id' => AdminRole::BranchAdmin->value, 'name' => 'Branch Admin'],
        ]);
    }
}
