<?php

namespace Database\Seeders;

use App\Models\Club;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Club::create([
            'name' => 'СКА',
            'coach_first_name' => 'Роман',
            'coach_middle_name' => 'Ротенберг',
            'coach_last_name' => 'Эгоистович',
            'foundation_year' => 1946,
            'division_id' => 1, // Привязка к дивизиону
            'coach_photo' => 'https://img.championat.com/c/900x900/news/big/q/u/novyj-glavnyj-trener-spartak.jpg',
        ]);

        Club::create([
            'name' => 'ЦСКА',
            'coach_first_name' => 'Миллер',
            'coach_middle_name' => 'Расманов',
            'coach_last_name' => 'Артемович',
            'foundation_year' => 1946,
            'division_id' => 2,
            'coach_photo' => 'https://img.championat.com/c/900x900/news/big/q/u/novyj-glavnyj-trener-spartak.jpg',
        ]);

        Club::create([
            'name' => 'Ак Барс',
            'coach_first_name' => 'Артур',
            'coach_middle_name' => 'Зяббаров',
            'coach_last_name' => 'Альбертович',
            'foundation_year' => 1956,
            'division_id' => 3,
            'coach_photo' => 'https://img.championat.com/c/900x900/news/big/q/u/novyj-glavnyj-trener-spartak.jpg',
        ]);
    }
}
