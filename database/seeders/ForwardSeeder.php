<?php

namespace Database\Seeders;

use App\Models\Forward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ForwardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Forward::create([
            'middle_name' => 'Овечкин',
            'club_id' => 1, // Привязка к клубу
            'goals_scored' => 30,
            'assists' => 25,
            'penalty_minutes' => 10,
        ]);

        Forward::create([
            'middle_name' => 'Малкин',
            'club_id' => 2,
            'goals_scored' => 25,
            'assists' => 30,
            'penalty_minutes' => 15,
        ]);

        Forward::create([
            'middle_name' => 'Кучеров',
            'club_id' => 3,
            'goals_scored' => 20,
            'assists' => 20,
            'penalty_minutes' => 5,
        ]);
    }
}
