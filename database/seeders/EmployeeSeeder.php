<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::where('role', 'manager')->first();

        User::create([
            'employee_id' => 'EMP1001',
            'name' => 'John Employee',
            'email' => 'employee@example.com',
            'mobile' => '7777777777',
            'department' => 'IT',
            'designation' => 'PHP Developer',
            'role' => 'employee',
            'manager_id' => $manager?->id,
            'status' => 'active',
            'password' => Hash::make('Employee@123'),
        ]);
    }
}
