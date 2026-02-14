<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            $question = Question::create([
                'question_text' => "This is question number $i?",
                'difficulty_level' => rand(1, 5),
            ]);

            $correct_option = rand(1, 4);
            $labels = ['A', 'B', 'C', 'D'];

            for ($j = 1; $j <= 4; $j++) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'label' => $labels[$j-1],
                    'option_text' => "This is option $j for question $i.",
                    'is_correct' => $j === $correct_option,
                ]);
            }
        }
    }
}
