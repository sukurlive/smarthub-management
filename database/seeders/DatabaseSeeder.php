<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
        
        // Member user
        User::create([
            'name' => 'Member User',
            'email' => 'member@example.com',
            'password' => Hash::make('password'),
            'role' => 'member'
        ]);
        
        // Equipment
        Equipment::create(['name' => 'Camera Sony A7III', 'description' => 'Full frame camera', 'status' => 'available']);
        Equipment::create(['name' => 'Tripod', 'description' => 'Professional tripod', 'status' => 'available']);
        Equipment::create(['name' => 'Studio Light', 'description' => 'LED studio lighting', 'status' => 'borrowed']);
        Equipment::create(['name' => 'Microphone', 'description' => 'Wireless microphone', 'status' => 'available']);
    }
}
