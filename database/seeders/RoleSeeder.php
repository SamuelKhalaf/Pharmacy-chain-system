<?php

namespace Database\Seeders;

use App\Enums\UserRole;
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
            ['id' => UserRole::SuperAdmin->value, 'name' => 'Super Admin'],
            ['id' => UserRole::BranchAdmin->value, 'name' => 'Branch Admin'],
            ['id' => UserRole::Customer->value, 'name' => 'Customer'],
        ]);
    }
}
