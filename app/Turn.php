<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Turn extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'event_date_id', 'name', 'initial_hour', 'final_hour', 'quantity', 'used', 
    ];

    public function eventDate() {
        return $this->belongsTo(EventDate::class);
    }

    public function access() {
        return $this->belongsToMany(Access::class);
    }
}
