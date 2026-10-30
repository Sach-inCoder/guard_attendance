<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            [
                'name' => 'Day Shift',
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'duration_minutes' => 720,
                'grace_period_minutes' => 15,
                'crosses_midnight' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Night Shift',
                'start_time' => '20:00:00',
                'end_time' => '08:00:00',
                'duration_minutes' => 720,
                'grace_period_minutes' => 15,
                'crosses_midnight' => true,
                'is_active' => true,
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::updateOrCreate(
                ['name' => $shift['name']],
                $shift
            );
        }
    }
}