<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventDate extends Model
{
    protected $fillable = [
        'event_id', 'date', 'initial_time', 'final_time', 
    ];

    public $timestamps = false;

    public function turns() {
        return $this->hasMany(Turn::class);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }
}
