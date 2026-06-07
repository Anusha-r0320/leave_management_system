<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::create([
            'employee_id' => 'EMP001',
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'mobile' => '9999999999',
            'department' => 'Administration',
            'designation' => 'System Admin',
            'role' => 'admin',
            'status' => 'active',
            'password' => Hash::make('Admin@123'),
        ]);
    }
}
