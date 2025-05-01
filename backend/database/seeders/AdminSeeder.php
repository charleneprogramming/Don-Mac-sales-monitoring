<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'Admin'],
            [
                'password' => Hash::make('admin123'),
                'name' => 'System Administrator',
                'contact_number' => '0000000000',
                'isAdmin' => 1
            ]
        );
    }
}
