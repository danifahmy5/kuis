<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'question_id',
        'contestant_id',
        'is_correct',
        'marked_by',
        'marked_at',
        'points_awarded',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'marked_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function contestant()
    {
        return $this->belongsTo(Contestant::class);
    }

    public function marker()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}
