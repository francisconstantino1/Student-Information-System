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
        // Create default admin account
        User::firstOrCreate(
            ['email' => 'admin@sis.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@sis.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $this->command->info('Admin account created successfully!');
        $this->command->info('Email: admin@sis.com');
        $this->command->info('Password: admin123');
    }
}

