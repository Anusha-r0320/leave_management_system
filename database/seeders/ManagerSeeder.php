<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'employee_id' => 'MGR001',
            'name' => 'Project Manager',
            'email' => 'manager@example.com',
            'mobile' => '8888888888',
            'department' => 'IT',
            'designation' => 'Manager',
            'role' => 'manager',
            'status' => 'active',
            'password' => Hash::make('Manager@123'),
        ]);
    }
}
