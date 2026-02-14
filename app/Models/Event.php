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
        'config',
        'created_by',
    ];

    protected $casts = [
        'config' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
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
