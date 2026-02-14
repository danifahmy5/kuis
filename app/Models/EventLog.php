<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'admin_id',
        'action_type',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
