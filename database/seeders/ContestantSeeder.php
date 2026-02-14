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
        Contestant::create(['name' => 'John Doe', 'team_name' => 'Team A']);
        Contestant::create(['name' => 'Jane Smith', 'team_name' => 'Team B']);
        Contestant::create(['name' => 'Peter Jones', 'team_name' => 'Team C']);
        Contestant::create(['name' => 'Mary Williams', 'team_name' => 'Team A']);
        Contestant::create(['name' => 'David Brown', 'team_name' => 'Team B']);
    }
}
