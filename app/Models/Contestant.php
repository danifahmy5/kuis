<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contestant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'team_name',
        'notes',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_contestants');
    }

    public function answers()
    {
        return $this->hasMany(EventAnswer::class);
    }
}
