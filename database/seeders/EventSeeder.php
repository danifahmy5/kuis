<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Contestant;
use App\Models\Event;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $event = Event::create([
            'title' => 'My First Quiz Event',
            'created_by' => Admin::first()->id,
        ]);

        // Attach contestants
        $contestants = Contestant::all();
        $event->contestants()->attach($contestants);

        // Attach questions
        $questions = Question::all();
        $seq = 1;
        foreach ($questions as $question) {
            $event->questions()->attach($question->id, ['seq' => $seq++]);
        }
    }
}
