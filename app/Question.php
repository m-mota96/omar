<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'event_id', 'title', 'information', 'required', 'type', 'options', 
    ];

    public function tickets() {
        return $this->belongsToMany(Ticket::class);
    }

    public function responses() {
        return $this->belongsToMany(Response::class);
    }
}
