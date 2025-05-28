<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Year;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->has(Year::factory()->count(1))
            ->createMany([
                [
                    'role_id' => 1,
                    'name' => 'test user admin',
                    'email' => 'test@example.com',
                    'password' => Hash::make('admin'), 
                ],
                [
                    'role_id' => 2,
                    'name' => 'test user Editor',
                    'email' => 'editor@example.com',
                    'password' => Hash::make('editor'), 
                ],
                [
                    'role_id' => 2,
                    'name' => 'test user Editor2',
                    'email' => 'editor2@example.com',
                    'password' => Hash::make('editor2'), 
                ],
            ]);
    }
}
