<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WorkSchedule;

class WorkScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkSchedule::create([
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'working_days' => '["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"]',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
