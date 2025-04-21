<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Year;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Role::factory()->createMany([
            ['name' => 'admin'],
            ['name' => 'editor'],
            ['name' => 'anonym'],
        ]);
        User::factory()->createMany([
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ],
            [
                'name' => 'Test User 2',
                'email' => 'test2@example.com',
            ],
            [
                'name' => 'Test User 3',
                'email' => 'test3@example.com',
            ],
        ]);

        Year::factory()->create([
            'year' => '2023',
            'user_id' => 1
        ]);

        Post::factory()->create([
            'title' => 'Test Post',
            'body' => 'This is a test post.',
            'user_id' => 1
        ]);
    }
}
