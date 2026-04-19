<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instances = [
            'Conseil d’établissement',
            'Commission de la Recherche',
            'Commission pédagogique',
            'Commission du suivi du budget et de maintenance',
            'Commission Scientifique',
        ];

        foreach ($instances as $instance) {
            \App\Models\Instance::firstOrCreate(['nom' => $instance]);
        }
    }
}
