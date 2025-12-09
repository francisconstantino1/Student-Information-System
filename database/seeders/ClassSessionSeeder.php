<?php

namespace Database\Seeders;

use App\Models\ClassSession;
use Illuminate\Database\Seeder;

class ClassSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sessions = [
            [
                'name' => 'Early Morning Session',
                'start_time' => '07:00:00',
                'end_time' => '08:00:00',
                'description' => 'Early morning class session from 7:00 AM to 8:00 AM',
                'is_active' => true,
            ],
            [
                'name' => 'Morning Session',
                'start_time' => '08:00:00',
                'end_time' => '09:00:00',
                'description' => 'Morning class session from 8:00 AM to 9:00 AM',
                'is_active' => true,
            ],
            [
                'name' => 'Late Morning Session',
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'description' => 'Late morning class session from 9:00 AM to 10:00 AM',
                'is_active' => true,
            ],
            [
                'name' => 'Mid-Morning Session',
                'start_time' => '10:00:00',
                'end_time' => '11:00:00',
                'description' => 'Mid-morning class session from 10:00 AM to 11:00 AM',
                'is_active' => true,
            ],
            [
                'name' => 'Noon Session',
                'start_time' => '11:00:00',
                'end_time' => '12:00:00',
                'description' => 'Noon class session from 11:00 AM to 12:00 PM',
                'is_active' => true,
            ],
            [
                'name' => 'Afternoon Session',
                'start_time' => '13:00:00',
                'end_time' => '14:00:00',
                'description' => 'Afternoon class session from 1:00 PM to 2:00 PM',
                'is_active' => true,
            ],
            [
                'name' => 'Late Afternoon Session',
                'start_time' => '14:00:00',
                'end_time' => '15:00:00',
                'description' => 'Late afternoon class session from 2:00 PM to 3:00 PM',
                'is_active' => true,
            ],
            [
                'name' => 'Evening Session',
                'start_time' => '17:00:00',
                'end_time' => '18:00:00',
                'description' => 'Evening class session from 5:00 PM to 6:00 PM',
                'is_active' => true,
            ],
        ];

        foreach ($sessions as $session) {
            ClassSession::create($session);
        }
    }
}
