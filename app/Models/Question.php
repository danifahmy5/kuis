<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'difficulty_level',
        'explanation',
    ];

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_questions');
    }
}
