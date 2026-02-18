<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::latest()->paginate(10);
        return view('admin.questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.questions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'difficulty_level' => 'required|integer|min:1|max:5',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0'
        ]);

        DB::transaction(function () use ($request) {
            $question = Question::create([
                'question_text' => $request->question_text,
                'difficulty_level' => $request->difficulty_level,
                'explanation' => $request->explanation,
            ]);

            foreach ($request->options as $index => $optionText) {
                // Generate label based on index (0 -> A, 1 -> B, etc)
                $label = chr(65 + $index); 
                
                QuestionOption::create([
                    'question_id' => $question->id,
                    'label' => $label,
                    'option_text' => $optionText,
                    'is_correct' => $index == $request->correct_option,
                ]);
            }
        });

        return redirect()->route('questions.index')
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        return view('admin.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        $question->load('options');
        return view('admin.questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'difficulty_level' => 'required|integer|min:1|max:5',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0'
        ]);

        DB::transaction(function () use ($request, $question) {
            $question->update([
                'question_text' => $request->question_text,
                'difficulty_level' => $request->difficulty_level,
                'explanation' => $request->explanation,
            ]);

            // For simplicity, delete old options and recreate them
            // In a more complex app, we might want to update existing IDs
            $question->options()->delete();

            foreach ($request->options as $index => $optionText) {
                // Generate label based on index (0 -> A, 1 -> B, etc)
                $label = chr(65 + $index); 
                
                QuestionOption::create([
                    'question_id' => $question->id,
                    'label' => $label,
                    'option_text' => $optionText,
                    'is_correct' => $index == $request->correct_option,
                ]);
            }
        });

        return redirect()->route('questions.index')
            ->with('success', 'Soal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->route('questions.index')
            ->with('success', 'Soal berhasil dihapus.');
    }
}
