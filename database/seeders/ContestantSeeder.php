<?php

namespace Database\Seeders;

use App\Models\Contestant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContestantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contestant::create(['name' => 'Mulyono', 'team_name' => 'Team A']);
        Contestant::create(['name' => 'Sumanto', 'team_name' => 'Team B']);

        $faker = fake('id_ID');
        for ($i = 0; $i < 5; $i++) {
            Contestant::create([
                'name' => $faker->name(),
                'team_name' => 'Team ' . $faker->randomElement(['A', 'B', 'C']),
            ]);
        }
    }
}
