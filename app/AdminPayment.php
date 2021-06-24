<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminPayment extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'amount', 'commission', 'status', 
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }
}
