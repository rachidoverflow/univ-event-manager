<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserInstanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear previous test users to avoid clutter
        \App\Models\User::where('email', 'like', '%@ucd.ac.ma')->delete();
        \App\Models\User::where('email', 'like', 'user%@univ.test')->delete();

        $instances = \App\Models\Instance::all();
        $roles = ['enseignant', 'fonctionnaire'];

        $moroccanNames = [
            'Mohammed El Amrani', 'Fatima Zohra Bennani', 'Youssef El Mansouri', 'Badr Eddine El Idrissi',
            'Souad Alami', 'Omar Tazi', 'Latifa Kadiri', 'Amine Belkhayat', 'Sanaa Chraibi', 'Khalid Nejjar',
            'Hassan Mourad', 'Khadija Radi', 'Said Jabiri', 'Meryem Zahid', 'Anas Serhani', 'Zineb Filali',
            'Rachid Boutayeb', 'Nadia Guessous', 'Hamza Lahlou', 'Layla Skali'
        ];

        foreach ($moroccanNames as $name) {
            $emailName = strtolower(str_replace(' ', '.', $name));
            $user = \App\Models\User::create([
                'name' => $name,
                'email' => "{$emailName}@ucd.ac.ma",
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => $roles[array_rand($roles)],
            ]);

            // Randomly assign to 1 or 2 instances
            $randomInstances = $instances->random(rand(1, 2));
            foreach ($randomInstances as $instance) {
                $instance->members()->attach($user->id);
            }
        }
    }
}
