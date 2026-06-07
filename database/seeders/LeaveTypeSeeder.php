<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeaveType::insert([
            [
                'name' => 'Casual Leave',
                'default_allocation' => 12,
                'status' => 'active',
            ],
            [
                'name' => 'Sick Leave',
                'default_allocation' => 10,
                'status' => 'active',
            ],
            [
                'name' => 'Earned Leave',
                'default_allocation' => 15,
                'status' => 'active',
            ],
        ]);
    }
}
