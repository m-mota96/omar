<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'event_id', 'email', 'phone', 'reference', 'amount', 'status', 
    ];

    public function accesses() {
        return $this->hasMany(Access::class);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }
}
