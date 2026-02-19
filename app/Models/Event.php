<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'started_at',
        'finished_at',
        'status',
        'is_intro',
        'quiz_started',
        'current_question_seq',
        'question_state',
        'timer_started_at',
        'timer_stopped_at',
        'created_by',
    ];

    protected $casts = [
        'is_intro' => 'boolean',
        'quiz_started' => 'boolean',
        'current_question_seq' => 'integer',
        'timer_started_at' => 'integer',
        'timer_stopped_at' => 'integer',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function contestants()
    {
        return $this->belongsToMany(Contestant::class, 'event_contestants');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'event_questions');
    }

    public function answers()
    {
        return $this->hasMany(EventAnswer::class);
    }

    public function logs()
    {
        return $this->hasMany(EventLog::class);
    }
}
