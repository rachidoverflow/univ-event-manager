<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@faculte.com'],
            [
                'name' => 'Secrétaire Général',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'ahmed@faculte.com'],
            [
                'name' => 'Professeur Ahmed',
                'password' => bcrypt('password'),
                'role' => 'participant',
            ]
        );

        $this->call([
            InstanceSeeder::class,
            UserInstanceSeeder::class,
        ]);
    }
}
