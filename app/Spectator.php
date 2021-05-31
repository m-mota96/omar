<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spectator extends Model
{
    protected $fillable = [
        'event_id', 'entry', 'exit', 
    ];
}
