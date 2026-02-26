<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'demo1@stagetracker.test'],
            [
                'name' => 'Demo User 1',
                'password' => 'password123',
            ]
        );

        User::updateOrCreate(
            ['email' => 'demo2@stagetracker.test'],
            [
                'name' => 'Demo User 2',
                'password' => 'password123',
            ]
        );
    }
}
