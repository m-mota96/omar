<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventDate extends Model
{
    protected $fillable = [
        'event_id', 'date', 'initial_time', 'final_time', 
    ];

    public $timestamps = false;
}
