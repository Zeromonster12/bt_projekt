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



        User::factory()
        ->has(Year::factory()->count(1))
        ->createMany([
            [
                'role_id' => 1,
                'name' => 'test user admin',
                'email' => 'test@example.com',
            ],
            [
                'role_id' => 2,
                'name' => 'test user Editor',
                'email' => 'editor@example.com',
            ],
            [
                'role_id' => 3,
                'name' => 'test user Anonym',
                'email' => 'anonym@example.com',
            ],
        ]);

        Post::factory()->create([
            'title' => 'Test Post',
            'body' => 'This is a test post.',
        ]);
    }
}
